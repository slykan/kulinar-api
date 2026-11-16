<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Urednički recepti za kolinje (krvavice, švargla, čvarci…) — originalni tekstovi.
 * Idempotentno po slugu, kao i 2026_10_26_000001_add_urednicki_recepti.
 */
return new class extends Migration
{
    private const EMAIL = 'urednistvo@kulinar.app';

    public function up(): void
    {
        $userId = DB::table('users')->where('email', self::EMAIL)->value('id')
            ?? DB::table('users')->insertGetId([
                'name' => 'Kulinar.app',
                'email' => self::EMAIL,
                'password' => Hash::make(Str::random(40)), // račun bez prijave; lozinku nitko ne zna
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        // slike za stranice o kolinju (naslovnica, kulen, vodič) idu na public disk kao i slike recepata
        foreach (['hero', 'kulen', 'kolinje-nekad'] as $ime) {
            Storage::disk('public')->put('kolinje/'.$ime.'.jpg', file_get_contents(database_path('data/kolinje/'.$ime.'.jpg')));
        }

        $vrijeme = Carbon::parse('2026-11-16 09:00:00');
        foreach (self::recepti() as $i => $r) {
            $r['image'] = self::slika($r['slika']);
            if (DB::table('posts')->where('slug', $r['slug'])->exists()) {
                DB::table('posts')->where('slug', $r['slug'])->where('user_id', $userId)->whereNull('image')->update(['image' => $r['image']]);

                continue;
            }
            $kada = $vrijeme->copy()->subHours($i * 3);
            DB::table('posts')->insert([
                'user_id' => $userId,
                'title' => $r['title'],
                'slug' => $r['slug'],
                'excerpt' => $r['excerpt'],
                'servings' => $r['servings'],
                'ingredients' => json_encode(array_map(fn ($s) => ['quantity' => $s[0], 'unit' => $s[1], 'name' => $s[2]], $r['sastojci']), JSON_UNESCAPED_UNICODE),
                'content' => implode("\n", array_map(fn ($k, $n) => ($n + 1).'. '.$k, $r['koraci'], array_keys($r['koraci']))),
                'image' => $r['image'],
                'published' => true,
                'published_at' => $kada,
                'created_at' => $kada,
                'updated_at' => $kada,
            ]);
        }
    }

    public function down(): void
    {
        $userId = DB::table('users')->where('email', self::EMAIL)->value('id');
        if ($userId) {
            DB::table('posts')->where('user_id', $userId)->whereIn('slug', array_column(self::recepti(), 'slug'))->delete();
        }
    }

    /** Kopira sliku iz database/data/kolinje na public disk (posts/), gdje je traže i web i aplikacija. */
    private static function slika(string $ime): ?string
    {
        $izvor = database_path('data/kolinje/'.$ime.'.jpg');
        if (! is_file($izvor)) {
            return null;
        }
        $put = 'posts/kolinje-'.$ime.'.jpg';
        Storage::disk('public')->put($put, file_get_contents($izvor));

        return $put;
    }

    /** [količina, jedinica, sastojak] */
    private static function recepti(): array
    {
        return [
            [
                'slug' => 'krvavice-domace',
                'slika' => 'krvavice',
                'title' => 'Domaće krvavice s rižom',
                'servings' => 10,
                'excerpt' => 'Klasične krvavice s kolinja: kuhano meso s glave i kože, riža, čvarci i svježa krv, začinjeni klinčićem, mažuranom i paprom. Kuhaju se odmah, a peku se kad zatrebaju.',
                'sastojci' => [
                    ['1', 'L', 'svježe svinjske krvi (procijeđene)'],
                    ['1', 'kg', 'kuhanog mesa s glave, kože i pluća'],
                    ['250', 'g', 'riže (kuhane je oko 0,5 kg)'],
                    ['500', 'g', 'čvaraka ili kuhane slanine'],
                    ['1', 'kom', 'velika glavica luka'],
                    ['50', 'g', 'soli'],
                    ['10', 'g', 'mljevenog papra'],
                    ['1', 'žličica', 'mljevenog klinčića ili pimenta'],
                    ['1', 'žlica', 'suhog mažurana'],
                    ['½', 'žličice', 'cimeta (po želji)'],
                    ['10', 'm', 'tankih svinjskih crijeva (28–32 mm)'],
                ],
                'koraci' => [
                    'Krv pri klanju hvataj u posudu i stalno miješaj rukom ili kuhačom 5–10 minuta, dok se ne izdvoje niti fibrina koje se izvade. Tako se krv neće zgrušati. Procijedi je i drži na hladnom.',
                    'Meso s glave, kožu i pluća kuhaj u posoljenoj vodi oko 2 sata, dok meso ne omekša. Ohladi, skini s kostiju i sameljaj na srednju rešetku. Juhu sačuvaj.',
                    'Rižu kuhaj u juhi od mesa dok ne bude napola mekana, oko 10 minuta, pa je ocijedi. Luk sitno nasjeckaj i popirjaj na malo masti dok ne postane staklast.',
                    'U velikoj posudi pomiješaj samljeveno meso, rižu, luk i sitno nasjeckane čvarke. Dodaj sol i začine i dobro izmiješaj.',
                    'Ulij krv i miješaj dok smjesa ne bude ujednačena i rijetka. Kuhaj mali komadić u vodi i probaj sol i začine prije punjenja.',
                    'Smjesu puni lijevkom ili strojem u pripremljena tanka crijeva, ne do kraja, jer se krvavice pri kuhanju šire. Krajeve zaveži ili spoji drvenim štapićem.',
                    'Krvavice stavi u vruću vodu (80–85 °C) i kuhaj 20–30 minuta, ali voda ne smije kipjeti jer pucaju. Kad ubodeš iglom i izađe bistra mast umjesto krvi, gotove su.',
                    'Izvadi ih u hladnu vodu da se brzo ohlade, pa ih složi na dasku da se ne dodiruju. Prije jela ih ispeci u pećnici na 200 °C 20–25 minuta, dok koža ne postane hrskava. Poslužuj s kiselim kupusom i krumpirom.',
                ],
            ],
            [
                'slug' => 'svargla-tlacenka',
                'slika' => 'svargla',
                'title' => 'Švargla (tlačenka)',
                'servings' => 12,
                'excerpt' => 'Švargla od kuhanog mesa s glave, jezika, srca i kože, začinjena češnjakom, paprom i paprikom i stisnuta u svinjski želudac. Jede se hladna, narezana na tanke ploške uz luk i ocat.',
                'sastojci' => [
                    ['1', 'kom', 'svinjska glava (bez mozga)'],
                    ['1', 'kom', 'svinjski jezik'],
                    ['1', 'kom', 'svinjsko srce'],
                    ['500', 'g', 'svinjske kože'],
                    ['1', 'kom', 'svinjski želudac (očišćen)'],
                    ['3', 'lista', 'lovora'],
                    ['1', 'žlica', 'papra u zrnu'],
                    ['15', 'g', 'soli po kg kuhanog mesa'],
                    ['1', 'glavica', 'češnjaka'],
                    ['2', 'žličice', 'slatke paprike'],
                    ['1', 'žličica', 'mljevenog papra'],
                    ['1', 'žličica', 'ljute paprike (po želji)'],
                ],
                'koraci' => [
                    'Glavu prepili na pola, dobro je operi i očisti dlake. Želudac okreni, ostruži, posoli i ostavi u hladnoj vodi s octom dok pripremaš ostalo.',
                    'Glavu, jezik, srce i kožu stavi u veliki lonac, prelij hladnom vodom, dodaj lovor i papar u zrnu i lagano kuhaj 2–3 sata, dok se meso lako ne odvaja od kosti.',
                    'Jeziku oguli kožicu dok je topao. Meso s glave skini s kostiju. Meso, jezik i srce nareži na kockice od oko 1 cm, a kožu i masnije dijelove sameljaj ili sitno nasjeckaj.',
                    'Izvaži sve meso i dodaj sol (15 g po kg), protisnuti češnjak, papriku i papar. Dolij oko 2 dl procijeđene juhe od kuhanja i dobro izmiješaj. Probaj i po potrebi dodaj soli.',
                    'Smjesu napuni u želudac, ali ne pretijesno. Otvor zašij jakim koncem ili zaveži. Želudac izbockaj iglom na nekoliko mjesta.',
                    'Kuhaj švarglu u istoj juhi na laganoj vatri 1–1,5 sat, ovisno o veličini. Voda ne smije jako kipjeti.',
                    'Izvadi je na dasku, prekrij drugom daskom i na nju stavi uteg od 5–10 kg. Ostavi na hladnom preko noći da se stisne i ohladi.',
                    'Sutradan švarglu po želji kratko dimi 1–2 dana ili je odmah jedi. Reži je na tanke ploške i poslužuj s lukom, octom i bučinim uljem. U hladnjaku traje oko tjedan dana.',
                ],
            ],
            [
                'slug' => 'cvarci-domaci',
                'slika' => 'cvarci',
                'title' => 'Domaći čvarci',
                'servings' => 10,
                'excerpt' => 'Zlatni, hrskavi čvarci od slanine s malo mesa, topljeni polako u kotlu ili velikom loncu. Uz njih se dobije i oko 6 litara bijele domaće masti.',
                'sastojci' => [
                    ['10', 'kg', 'slanine s malo mesa (s potrbušine i leđa)'],
                    ['1', 'L', 'vode'],
                    ['2', 'dl', 'mlijeka (po želji)'],
                    ['40', 'g', 'soli'],
                ],
                'koraci' => [
                    'Slaninu nareži na kocke od 2–3 cm. Ako ostavljaš kožu, kocke s kožom neka budu nešto manje da se koža stigne ispeći.',
                    'U kotao ili veliki lonac debelog dna ulij vodu i dodaj slaninu. Stavi na srednju vatru.',
                    'Miješaj često drvenom lopaticom, do samog dna. Najprije voda proključa i ispari, a onda se kocke počnu pržiti u masti koja se iz njih topi. Taj dio traje 1–1,5 sat.',
                    'Kad se čvarci počnu zlatiti, miješaj stalno jer od tog trenutka brzo potamne. Ako želiš ljepšu boju, sada dodaj mlijeko — mast će zapjeniti, pa pazi da ne prekipi.',
                    'Čvarci su gotovi kad su zlatnosmeđi, počnu tonuti, a mast se izbistri. Odmah ih izvadi cjediljkom.',
                    'Mast procijedi kroz gazu u čiste, suhe staklenke. Čvarke dok su vrući stisni u preši za čvarke, a iscijeđenu mast dodaj ostaloj.',
                    'Posoli čvarke dok su još topli i promiješaj. Poslužuj ih uz kruh i luk, a ostatak spremi u hladnjak ili zamrzni u porcijama.',
                ],
            ],
            [
                'slug' => 'pogacice-sa-cvarcima',
                'slika' => 'pogacice',
                'title' => 'Pogačice sa čvarcima',
                'servings' => 30,
                'excerpt' => 'Prhke, slojevite pogačice s mljevenim čvarcima, kakve se rade nakon kolinja. Tijesto se razvlači i preklapa nekoliko puta, pa se pogačice lijepo dižu u listove.',
                'sastojci' => [
                    ['500', 'g', 'glatkog brašna'],
                    ['20', 'g', 'svježeg kvasca (ili 7 g suhog)'],
                    ['250', 'ml', 'mlakog mlijeka'],
                    ['1', 'žličica', 'šećera'],
                    ['1', 'kom', 'jaje'],
                    ['1', 'dl', 'kiselog vrhnja'],
                    ['1', 'žličica', 'soli'],
                    ['250', 'g', 'mljevenih čvaraka'],
                    ['1', 'kom', 'žumanjak za premazivanje'],
                    ['1', 'žlica', 'mlijeka za premazivanje'],
                ],
                'koraci' => [
                    'Kvasac razmuti u mlakom mlijeku sa šećerom i ostavi 10 minuta da se zapjeni.',
                    'U brašno dodaj sol, jaje, kiselo vrhnje i kvasac i zamijesi mekano, glatko tijesto. Pokrij ga i ostavi na toplom da se digne, oko 45 minuta.',
                    'Čvarke sameljaj u stroju za meso ili sjeckalici dok ne postanu glatka pasta. Ako su suhi, dodaj žlicu masti.',
                    'Dignuto tijesto razvaljaj u pravokutnik debljine oko 1 cm i premaži trećinom čvaraka. Preklopi ga kao pismo, na tri dijela, pa ga ostavi 15 minuta da odmori.',
                    'Ponovi razvlačenje, premazivanje i preklapanje još dva puta, svaki put s trećinom čvaraka i odmorom od 15 minuta.',
                    'Na kraju razvaljaj tijesto na debljinu 2 cm, nožem ga zareži u mrežicu i vadi pogačice okruglim rezačem promjera 5 cm.',
                    'Složi ih na lim obložen papirom za pečenje, premaži žumanjkom razmućenim s mlijekom i ostavi da se dižu još 20 minuta.',
                    'Peci u pećnici zagrijanoj na 200 °C oko 20 minuta, dok ne porumene. Najbolje su tople, a u zatvorenoj kutiji ostaju mekane 2–3 dana.',
                ],
            ],
            [
                'slug' => 'pecene-kobasice-s-kiselim-kupusom',
                'slika' => 'kobasice-kupus',
                'title' => 'Pečene kobasice s kiselim kupusom i krumpirom',
                'servings' => 4,
                'excerpt' => 'Jednostavan zimski ručak iz jedne posude: svježe domaće kobasice pečene na kiselom kupusu i krumpiru, koji upiju sav sok i masnoću od kobasica.',
                'sastojci' => [
                    ['8', 'kom', 'svježih domaćih kobasica (oko 800 g)'],
                    ['800', 'g', 'kiselog kupusa (ribanca)'],
                    ['800', 'g', 'krumpira'],
                    ['1', 'kom', 'glavica luka'],
                    ['2', 'žlice', 'masti ili ulja'],
                    ['1', 'žličica', 'slatke paprike'],
                    ['½', 'žličice', 'kima (po želji)'],
                    ['2', 'lista', 'lovora'],
                    ['1', 'dl', 'bijelog vina ili vode'],
                    ['', '', 'papar'],
                ],
                'koraci' => [
                    'Pećnicu zagrij na 200 °C. Kiseli kupus kratko isperi ako je jako kiseo i dobro ga ocijedi.',
                    'Krumpir oguli i nareži na kriške debljine 1 cm. Luk nareži na tanke polukolutove.',
                    'Na masti kratko popirjaj luk, dodaj papriku i odmah zatim kupus, kim, lovor i papar. Promiješaj i podlij vinom.',
                    'Kupus rasporedi u veću vatrostalnu posudu, a po njemu složi kriške krumpira. Krumpir lagano posoli i premaži s malo masti.',
                    'Kobasice izbockaj vilicom na nekoliko mjesta i složi ih po krumpiru.',
                    'Peci oko 45 minuta, dok kobasice ne dobiju boju i krumpir ne omekša. Na pola pečenja kobasice okreni i podlij s malo vode ako se kupus suši.',
                    'Kobasice moraju biti pečene i u sredini (71 °C na termometru). Poslužuj odmah, uz senf i domaći kruh.',
                ],
            ],
        ];
    }
};
