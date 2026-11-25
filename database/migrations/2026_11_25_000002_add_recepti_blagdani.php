<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Urednički blagdanski recepti 25.11.2026. (advent, Badnjak, Božić, Stara godina) — originalni tekstovi.
 * Slike u database/data/recepti (blagdani-*.jpg), izvori u config/slike.php. Idempotentno po slugu.
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

        $vrijeme = Carbon::parse('2026-11-25 12:00:00');
        foreach (self::recepti() as $i => $r) {
            $r['image'] = self::slika($r['slika']);
            if (DB::table('posts')->where('slug', $r['slug'])->exists()) {
                DB::table('posts')->where('slug', $r['slug'])->where('user_id', $userId)->whereNull('image')->update(['image' => $r['image']]);

                continue;
            }
            $kada = $vrijeme->copy()->subHours($i * 2);
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

    /** Kopira sliku iz database/data/recepti na public disk (posts/), gdje je traže i web i aplikacija. */
    private static function slika(string $ime): ?string
    {
        $izvor = database_path('data/recepti/'.$ime.'.jpg');
        if (! is_file($izvor)) {
            return null;
        }
        $put = 'posts/recept-'.$ime.'.jpg';
        Storage::disk('public')->put($put, file_get_contents($izvor));

        return $put;
    }

    /** [količina, jedinica, sastojak] */
    private static function recepti(): array
    {
        return [
            [
                'slug' => 'kuhano-vino-crno',
                'slika' => 'blagdani-kuhano-vino',
                'title' => 'Kuhano vino (crno, s narančom i cimetom)',
                'servings' => 6,
                'excerpt' => 'Adventsko kuhano vino kakvo se pije na božićnim sajmovima: crno vino zagrijano s narančom, cimetom, klinčićima i malo meda. Gotovo za deset minuta, a tajna je da nikad ne zakipi.',
                'sastojci' => [
                    ['1', 'L', 'suhog crnog vina (frankovka, plavac, merlot)'],
                    ['1', 'kom', 'naranča'],
                    ['½', 'kom', 'limuna'],
                    ['2', 'štapića', 'cimeta'],
                    ['6', 'kom', 'klinčića'],
                    ['2', 'kom', 'zvjezdastog anisa (po želji)'],
                    ['80–120', 'g', 'šećera ili 3–4 žlice meda'],
                    ['200', 'ml', 'vode ili soka od jabuke'],
                    ['1', 'mrvica', 'muškatnog oraščića'],
                ],
                'koraci' => [
                    'Naranču i limun dobro operi vrućom vodom, jer kora ide u vino. Pola naranče nareži na kolutove, a iz druge polovice iscijedi sok.',
                    'U lonac stavi vodu ili sok od jabuke, šećer, cimet, klinčiće, anis i oraščić. Zagrijavaj dok se šećer ne otopi i dok začini ne zamirišu, 3 do 4 minute.',
                    'Ulij vino, dodaj sok i kolutove naranče i limuna. Zagrijavaj na laganoj vatri dok vino ne postane vruće, ali ne smije zakipjeti. Na oko 70 °C alkohol još ne isparava, a vino ne postaje gorko.',
                    'Makni s vatre, poklopi i ostavi 5 do 10 minuta da se okusi prožmu. Probaj i po potrebi dosladi medom.',
                    'Procijedi i poslužuj vruće u šalicama ili debljim čašama, s kolutom naranče i štapićem cimeta. Ostatak možeš drugi dan lagano podgrijati, samo ga opet nemoj kuhati.',
                    'Bijelo kuhano vino: umjesto crnog uzmi suho bijelo vino (graševina, malvazija), izostavi anis i dodaj jednu jabuku narezanu na kriške i malo svježeg đumbira.',
                ],
            ],
            [
                'slug' => 'bakalar-na-bijelo',
                'slika' => 'blagdani-bakalar',
                'title' => 'Bakalar na bijelo za Badnjak',
                'servings' => 8,
                'excerpt' => 'Tradicionalni bakalar na bijelo s Badnje večeri: dobro namočen i kuhan bakalar, izmrvljen i dugo miješan s maslinovim uljem, češnjakom i peršinom u kremasti namaz. Jede se s kruhom ili palentom.',
                'sastojci' => [
                    ['500', 'g', 'suhog bakalara (stockfish)'],
                    ['200–250', 'ml', 'dobrog maslinovog ulja'],
                    ['4–5', 'češnja', 'češnjaka'],
                    ['1', 'veza', 'peršina'],
                    ['200', 'g', 'krumpira (po želji, za mekšu smjesu)'],
                    ['', '', 'sol i svježe mljeveni papar'],
                    ['1', 'kom', 'lovorov list'],
                ],
                'koraci' => [
                    'Bakalar je tvrd kao drvo pa ga treba omekšati. Ako je cijeli, istuci ga drvenim batom, pa ga stavi u veliku posudu s hladnom vodom i drži na hladnom 2 do 3 dana. Vodu mijenjaj barem dvaput dnevno.',
                    'Namočeni bakalar stavi u lonac s hladnom vodom i lovorom. Kad zakipi, smanji vatru i kuhaj ga lagano 45 do 60 minuta, dok meso ne počne lako otpadati s kostiju. Krumpir, ako ga koristiš, kuhaj u istoj vodi zadnjih 20 minuta.',
                    'Bakalar ocijedi, ali sačuvaj šalicu vode od kuhanja. Dok je još topao, očisti ga od kože i svih kostiju. To je najsporiji dio posla pa ga radi pažljivo.',
                    'Meso izmrvi prstima ili vilicom u veliku zdjelu. Krumpir zgnječi i dodaj ga bakalaru.',
                    'Češnjak i peršin sitno nasjeckaj. Kuhačom snažno miješaj bakalar i postupno, u tankom mlazu, dolijevaj ulje i povremeno žlicu vode od kuhanja. Miješaj 10 do 15 minuta, dok smjesa ne postane bijela, kremasta i kao pjenasta.',
                    'Umiješaj češnjak i peršin, posoli i popapri. Bakalar je najbolji kad nekoliko sati odstoji, pa ga napravi ujutro ili dan ranije. Poslužuj na sobnoj temperaturi s prepečenim kruhom ili pečenom palentom.',
                ],
            ],
            [
                'slug' => 'purica-s-mlincima',
                'slika' => 'blagdani-mlinci',
                'title' => 'Purica s mlincima za Božić',
                'servings' => 6,
                'excerpt' => 'Božićna purica pečena polako da ostane sočna, s hrskavom kožom, i domaći mlinci preliveni sokom od pečenja. Recept je za puricu od oko 3,5 kg, s planom što napraviti dan ranije.',
                'sastojci' => [
                    ['3,5', 'kg', 'purice (cijele ili bataka i prsa)'],
                    ['100', 'g', 'svinjske masti ili maslaca'],
                    ['4', 'češnja', 'češnjaka'],
                    ['1', 'žlica', 'soli'],
                    ['1', 'žličica', 'papra'],
                    ['2', 'grančice', 'ružmarina ili majčine dušice'],
                    ['300', 'ml', 'vode ili temeljca'],
                    ['500', 'g', 'glatkog brašna (za mlince)'],
                    ['2', 'kom', 'jaja (za mlince)'],
                    ['200', 'ml', 'mlake vode (za mlince)'],
                    ['1', 'prstohvat', 'soli (za mlince)'],
                ],
                'koraci' => [
                    'Dan ranije: puricu operi, osuši papirnatim ručnikom i iznutra i izvana natrljaj solju, paprom i zgnječenim češnjakom. Pokrivenu je drži preko noći u hladnjaku.',
                    'Mlinci (mogu i dan-dva ranije): zamijesi glatko tijesto od brašna, jaja, vode i soli. Podijeli ga na 6 dijelova i svaki razvaljaj vrlo tanko. Peci ih na suhoj ploči štednjaka ili na limu u pećnici na 220 °C po 3 do 4 minute, dok ne dobiju smeđe mjehuriće. Ohlađene čuvaj u vrećici.',
                    'Puricu izvadi iz hladnjaka sat vremena prije pečenja. Premaži je mašću ili maslacem, stavi ružmarin u šupljinu i složi je u pekač s prsima prema gore. Podlij vodom.',
                    'Peci pokrivenu (poklopac ili folija) na 170 °C oko 2,5 sata, računajući oko 40 minuta po kilogramu. Svakih 30 minuta je prelij sokom iz pekača.',
                    'Skini poklopac, pojačaj na 200 °C i peci još 30 do 40 minuta, dok koža ne postane zlatna i hrskava. Purica je gotova kad je u najdebljem dijelu prsa 72 °C, a u bataku 80 °C. Bez termometra: probodi batak, sok mora biti bistar.',
                    'Puricu izvadi na dasku, pokrij folijom i ostavi 15 minuta da se sokovi slegnu. Za to vrijeme mlince izlomi na komade veličine dlana.',
                    'Mlince prelij kipućom posoljenom vodom i odmah ih ocijedi, za minutu do dvije. Stavi ih u pekač u sok od pečenja, promiješaj i zapeci u pećnici 10 minuta, dok ne upiju sok i dobiju hrskave rubove.',
                    'Puricu nareži i poslužuj s mlincima, kiselim kupusom ili zelenom salatom.',
                ],
            ],
            [
                'slug' => 'orahnjaca-bozicna',
                'slika' => 'blagdani-orahnjaca',
                'title' => 'Božićna orahnjača',
                'servings' => 16,
                'excerpt' => 'Mekana kvasna orahnjača s puno nadjeva od oraha, meda i limunove korice. Recept je za dvije štruce, a drugi dan je još sočnija.',
                'sastojci' => [
                    ['500', 'g', 'glatkog brašna'],
                    ['20', 'g', 'svježeg kvasca (ili 7 g suhog)'],
                    ['250', 'ml', 'mlakog mlijeka (za tijesto)'],
                    ['80', 'g', 'šećera (za tijesto)'],
                    ['100', 'g', 'otopljenog maslaca'],
                    ['2', 'kom', 'žumanjka'],
                    ['1', 'prstohvat', 'soli'],
                    ['1', 'kom', 'naribana korica limuna'],
                    ['400', 'g', 'mljevenih oraha (za nadjev)'],
                    ['150', 'g', 'šećera (za nadjev)'],
                    ['150', 'ml', 'mlijeka (za nadjev)'],
                    ['2', 'žlice', 'meda (za nadjev)'],
                    ['1', 'žlica', 'ruma (za nadjev)'],
                    ['1', 'kom', 'jaje za premazivanje'],
                ],
                'koraci' => [
                    'Kvasac razmuti u malo mlakog mlijeka sa žličicom šećera i ostavi 10 minuta da se zapjeni.',
                    'U zdjelu prosij brašno, dodaj šećer, sol i limunovu koricu, pa ulij kvasac, ostatak mlijeka, žumanjke i otopljeni maslac. Mijesi 10 minuta, dok tijesto ne postane glatko i ne počne se odvajati od zdjele.',
                    'Pokrij tijesto i ostavi ga na toplom 1 sat, dok se ne udvostruči.',
                    'Nadjev: mlijeko zagrij sa šećerom i medom, prelij preko mljevenih oraha, dodaj rum i promiješaj. Ostavi da se ohladi. Nadjev mora biti mazav, ne tekuć.',
                    'Tijesto podijeli na dva dijela i svaki razvaljaj u pravokutnik debljine oko pola centimetra. Ravnomjerno ga premaži nadjevom, a rubove ostavi slobodne 1 cm.',
                    'Čvrsto ga zarolaj od duže strane, krajeve podvij i štruce stavi u kalupe ili na lim obložen papirom za pečenje, sa spojem prema dolje. Ostavi ih da se dižu još 30 minuta.',
                    'Premaži ih razmućenim jajetom, probodi vilicom na nekoliko mjesta i peci na 175 °C 40 do 45 minuta, dok ne postanu tamnozlatne. Ako prebrzo tamne, pokrij ih papirom za pečenje.',
                    'Ostavi ih da se potpuno ohlade, pa tek onda reži. Zamotana u krpu ili foliju orahnjača ostaje mekana 4 do 5 dana.',
                ],
            ],
            [
                'slug' => 'francuska-salata',
                'slika' => 'blagdani-francuska-salata',
                'title' => 'Francuska salata za Novu godinu',
                'servings' => 8,
                'excerpt' => 'Klasična francuska salata s dočeka Nove godine: kuhano povrće izrezano na sitne kockice, kiseli krastavci i jaja, povezani majonezom i malo kiselog vrhnja da ne bude preteška.',
                'sastojci' => [
                    ['4', 'kom', 'srednja krumpira'],
                    ['3', 'kom', 'mrkve'],
                    ['1', 'kom', 'korijen peršina (po želji)'],
                    ['200', 'g', 'graška (smrznutog ili iz konzerve)'],
                    ['5', 'kom', 'kiselih krastavaca'],
                    ['4', 'kom', 'jaja'],
                    ['1', 'kom', 'kisela jabuka (po želji)'],
                    ['300', 'g', 'majoneze'],
                    ['150', 'g', 'kiselog vrhnja ili grčkog jogurta'],
                    ['1', 'žlica', 'senfa'],
                    ['1', 'žlica', 'soka od limuna'],
                    ['', '', 'sol i papar'],
                ],
                'koraci' => [
                    'Krumpir operi i skuhaj ga s korom, oko 25 minuta, dok nije mekan, ali se ne raspada. Mrkvu i peršin očisti i skuhaj posebno, oko 15 minuta. Grašak kuhaj 3 minute, a jaja 10 minuta.',
                    'Sve ohladi potpuno, a najbolje preko noći u hladnjaku. Toplo povrće s majonezom daje kašastu salatu, a hladno se lijepo reže na kockice i ostaje čvrsto.',
                    'Krumpir oguli. Krumpir, mrkvu, peršin, krastavce, jaja i jabuku nareži na jednake sitne kockice, otprilike veličine zrna graška.',
                    'U zdjeli pomiješaj majonezu, kiselo vrhnje, senf i limunov sok. Probaj i posoli.',
                    'Povrće lagano izmiješaj s preljevom, tek toliko da se sve poveže. Ostavi malo preljeva sa strane i dodaj ga pred posluživanje ako se salata osuši.',
                    'Salatu ostavi u hladnjaku barem 2 sata da se okusi povežu. Ukrasi je kolutovima jaja, krastavcima ili peršinom. U hladnjaku traje 3 dana.',
                ],
            ],
            [
                'slug' => 'medenjaci-bozicni',
                'slika' => 'blagdani-medenjaci',
                'title' => 'Božićni medenjaci',
                'servings' => 40,
                'excerpt' => 'Mirisni medenjaci s medom i začinima za kolače, koji nakon nekoliko dana u limenci postanu mekani. Odlični za ukrašavanje s djecom i za vješanje na bor.',
                'sastojci' => [
                    ['500', 'g', 'glatkog brašna'],
                    ['200', 'g', 'meda'],
                    ['100', 'g', 'šećera'],
                    ['80', 'g', 'maslaca'],
                    ['2', 'kom', 'jaja'],
                    ['1', 'žličica', 'sode bikarbone'],
                    ['2', 'žličice', 'mljevenog cimeta'],
                    ['½', 'žličice', 'mljevenih klinčića'],
                    ['½', 'žličice', 'mljevenog đumbira'],
                    ['1', 'kom', 'naribana korica naranče'],
                    ['1', 'kom', 'bjelanjak i 200 g šećera u prahu (za glazuru)'],
                ],
                'koraci' => [
                    'Med, šećer i maslac lagano zagrij dok se sve ne otopi, ali neka ne zakipi. Ostavi da se smjesa ohladi do mlakog.',
                    'Brašno pomiješaj sa sodom bikarbonom, začinima i koricom naranče. Dodaj medenu smjesu i jaja i zamijesi glatko, malo ljepljivo tijesto.',
                    'Tijesto zamotaj u foliju i ostavi ga u hladnjaku barem 2 sata, a najbolje preko noći. Hladno se tijesto puno lakše valja.',
                    'Razvaljaj ga na pobrašnjenoj podlozi na debljinu 4 do 5 mm i vadi oblike: srca, zvijezde, jelke. Ako ih želiš objesiti na bor, slamkom probuši rupicu.',
                    'Peci na papiru za pečenje na 175 °C 8 do 10 minuta. Moraju ostati svijetli i mekani na dodir, jer se na zraku stvrdnu.',
                    'Za glazuru umuti bjelanjak sa šećerom u prahu u gustu bijelu kremu i ukrašavaj ohlađene medenjake vrećicom s tankim otvorom.',
                    'Kad se glazura osuši, složi ih u limenku s kriškom jabuke. Za 3 do 5 dana postanu mekani i još mirisniji, a traju do Nove godine.',
                ],
            ],
            [
                'slug' => 'vanilin-kiflice',
                'slika' => 'blagdani-vanilin-kiflice',
                'title' => 'Vanilin kiflice',
                'servings' => 60,
                'excerpt' => 'Prhke vanilin kiflice s mljevenim orasima ili bademima, uvaljane u vanilin šećer dok su još tople. Klasik adventskog tanjura koji se topi u ustima.',
                'sastojci' => [
                    ['280', 'g', 'glatkog brašna'],
                    ['200', 'g', 'hladnog maslaca'],
                    ['100', 'g', 'mljevenih oraha ili badema'],
                    ['70', 'g', 'šećera u prahu'],
                    ['2', 'kom', 'žumanjka'],
                    ['1', 'prstohvat', 'soli'],
                    ['2', 'vrećice', 'vanilin šećera (u tijesto)'],
                    ['100', 'g', 'šećera u prahu i 2 vrećice vanilin šećera (za valjanje)'],
                ],
                'koraci' => [
                    'Brašno, orahe, šećer u prahu, sol i vanilin šećer pomiješaj u zdjeli. Dodaj hladan maslac narezan na kockice i prstima ga utrljaj u brašno dok ne dobiješ mrvice.',
                    'Dodaj žumanjke i brzo zamijesi glatko tijesto. Ne mijesi predugo, jer se maslac zagrije i kiflice nisu prhke.',
                    'Tijesto zamotaj u foliju i ostavi ga u hladnjaku 1 sat.',
                    'Odvajaj komadiće veličine oraha, razvaljaj ih u valjčiće debljine prsta i savij u polumjesec. Slaži ih na lim obložen papirom za pečenje, s malim razmakom.',
                    'Peci na 175 °C 10 do 12 minuta. Moraju ostati svijetle, s tek blago zlatnim vrhovima.',
                    'Pomiješaj šećer u prahu i vanilin šećer. Kiflice ostavi minutu-dvije da se malo stegnu, pa ih još tople pažljivo uvaljaj u šećer. Vruće su krhke, a hladne ne prime šećer.',
                    'Ohlađene ih čuvaj u limenci. Traju 2 do 3 tjedna, pa ih možeš ispeći već početkom adventa.',
                ],
            ],
        ];
    }
};
