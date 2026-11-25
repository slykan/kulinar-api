<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Urednički recepti 25.11.2026.: domaće kobasice, pečene kobasice, kiseli kupus (originalni tekstovi)
 * + slika za postojeću sarmu (sarma-od-kiselog-kupusa). Slike u database/data/recepti, izvori u config/slike.php.
 * Idempotentno po slugu, kao i ranije migracije s receptima.
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

        // postojeća urednička sarma dobiva sliku (samo ako je još nema)
        DB::table('posts')->where('slug', 'sarma-od-kiselog-kupusa')->where('user_id', $userId)->whereNull('image')
            ->update(['image' => self::slika('sarma')]);

        $vrijeme = Carbon::parse('2026-11-25 09:00:00');
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
            DB::table('posts')->where('slug', 'sarma-od-kiselog-kupusa')->where('user_id', $userId)->where('image', 'posts/recept-sarma.jpg')->update(['image' => null]);
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
                'slug' => 'domace-kobasice-slavonske',
                'slika' => 'kobasice-punjenje',
                'title' => 'Domaće kobasice (slavonske, s paprikom i češnjakom)',
                'servings' => 20,
                'excerpt' => 'Prave domaće kobasice od svinjskog mesa i slanine, začinjene slatkom i ljutom paprikom, paprom i češnjakom. Recept je za 5 kg nadjeva: od mljevenja i punjenja do dimljenja i sušenja.',
                'sastojci' => [
                    ['3,5', 'kg', 'svinjskog mesa (but, vrat, lopatica)'],
                    ['1,5', 'kg', 'čvrste leđne slanine ili masnijeg mesa'],
                    ['90', 'g', 'soli (18 g po kilogramu)'],
                    ['100', 'g', 'slatke mljevene paprike'],
                    ['20–40', 'g', 'ljute mljevene paprike (po ukusu)'],
                    ['15', 'g', 'mljevenog crnog papra'],
                    ['40', 'g', 'češnjaka (1 veća glavica)'],
                    ['1', 'žličica', 'šećera'],
                    ['1', 'žličica', 'mljevenog kima (po želji)'],
                    ['10', 'm', 'tankih svinjskih crijeva (32–36 mm)'],
                ],
                'koraci' => [
                    'Meso i slaninu nareži na kocke i ohladi na 0–2 °C, a slaninu je najbolje i lagano zamrznuti. Hladno meso se lijepo melje i masnoća se ne razmazuje.',
                    'Crijeva dobro isperi, namoči u mlakoj vodi s malo octa oko sat vremena i provuci vodu kroz njih da vidiš ima li rupa.',
                    'Meso i slaninu sameljaj na rešetku od 6 do 8 mm. Češnjak očisti, zgnječi i prelij s 100 ml mlake vode, pa ga ostavi 15 minuta i procijedi. Za kobasice koristi samo tu češnjakovu vodu, tako nećeš imati komadiće koji brzo užegnu.',
                    'U nadjev dodaj sol, obje paprike, papar, šećer, kim i češnjakovu vodu. Mijesi rukama 5 do 10 minuta, dok nadjev ne postane ljepljiv i ne počne se držati za dlan. Tada će kobasice biti sočne i neće se mrviti.',
                    'Ispeci mali komadić nadjeva na tavi i probaj. Sada je zadnji trenutak za još soli ili ljute paprike. Pokriven nadjev ostavi preko noći na hladnom, da se začini prožmu.',
                    'Puni crijeva strojem ili lijevkom, čvrsto ali ne do pucanja. Iglom probuši mjehuriće zraka, pa kobasice zavrni ili poveži u parove dužine 20 do 25 cm.',
                    'Objesi ih na hladno i prozračno mjesto 1 do 2 dana da se ocijede i prosuše izvana. Zatim ih dimi hladnim dimom od bukve ili graba 5 do 7 dana, po nekoliko sati dnevno, tako da dim ne bude topao.',
                    'Nakon dimljenja suši ih na 10–15 °C i oko 75–80 % vlage. Za 3 do 5 tjedana izgube trećinu težine i tada su gotove. Ako ih želiš jesti svježe, peci ih već nakon prosušivanja ili kratkog dimljenja.',
                ],
            ],
            [
                'slug' => 'pecene-domace-kobasice-pecnica-rostilj',
                'slika' => 'kobasice-pecene',
                'title' => 'Pečene domaće kobasice (u pećnici ili na roštilju)',
                'servings' => 4,
                'excerpt' => 'Kako ispeći svježe ili polusuhe domaće kobasice da ostanu sočne, a koža bude hrskava i ne puca: u pećnici s lukom i malo vina ili na roštilju na srednjoj vatri.',
                'sastojci' => [
                    ['8', 'kom', 'svježih ili polusuhih domaćih kobasica (oko 1 kg)'],
                    ['2', 'kom', 'glavice crvenog luka'],
                    ['100', 'ml', 'bijelog vina ili vode'],
                    ['1', 'žlica', 'svinjske masti ili ulja'],
                    ['2', 'grančice', 'ružmarina ili lovorov list (po želji)'],
                    ['', '', 'senf, hren i kruh za posluživanje'],
                ],
                'koraci' => [
                    'Kobasice izvadi iz hladnjaka pola sata ranije, da se ne peku hladne. Nemoj ih bušiti vilicom: kroz rupe izlazi sok, a s njim i okus.',
                    'Pećnica: zagrij je na 180 °C. Luk nareži na deblje kolutove i složi ga na dno lima premazanog mašću, pa na njega složi kobasice. Podlij vinom ili vodom i dodaj ružmarin.',
                    'Peci 30 do 35 minuta i kobasice jednom okreni. Tekućina i luk sprečavaju da koža odmah pukne, a meso se peče ravnomjerno.',
                    'Za kraj pojačaj na 220 °C i peci još 8 do 10 minuta, dok koža ne dobije boju i postane hrskava. Kobasice su gotove kad je unutra 72 °C, odnosno kad sok koji izlazi više nije ružičast.',
                    'Roštilj: kobasice peci na srednjoj, ne prejakoj vatri, oko 15 do 20 minuta. Okreći ih svake 2 do 3 minute. Ako je žar prejak, koža pukne prije nego što se kobasica ispeče iznutra. Polusuhe kobasice možeš prije pečenja 5 minuta kuhati u vrućoj vodi pa ih samo zapeći.',
                    'Poslužuj odmah, s pečenim lukom iz lima, senfom, svježim hrenom i kruhom. Odlično idu uz kiseli kupus, pire krumpir ili grah salatu.',
                ],
            ],
            [
                'slug' => 'kiseli-kupus-domaci-ukiseljavanje',
                'slika' => 'kiseli-kupus',
                'title' => 'Domaći kiseli kupus: ribani u staklenkama i glavice za sarmu',
                'servings' => 10,
                'excerpt' => 'Kako ukiseliti kupus kod kuće na dva načina: ribani u staklenkama ili posudi i cijele glavice u bačvi za sarmu. Samo kupus, sol i vrijeme, bez octa i konzervansa.',
                'sastojci' => [
                    ['10', 'kg', 'zbijenih glavica bijelog kupusa (kasni, jesenski)'],
                    ['200', 'g', 'krupne nejodirane soli za ribani kupus (2 % težine)'],
                    ['50–60', 'g', 'soli po litri vode za salamuru za glavice'],
                    ['4–5', 'kom', 'lovorovih listova'],
                    ['1', 'žlica', 'crnog papra u zrnu'],
                    ['1', 'komad', 'korijena hrena (po želji)'],
                    ['1', 'šaka', 'kukuruznih zrna ili kora kruha (po želji, za brže vrenje)'],
                ],
                'koraci' => [
                    'Kupus očisti od vanjskih, oštećenih listova i operi. Za kiseljenje uzmi čvrste, teške glavice kasnog kupusa, jer daju najviše soka i ostaju hrskave.',
                    'Ribani kupus: glavice prepolovi, izreži košćak i naribaj kupus na tanke rezance. Važi ga i na svaki kilogram dodaj 20 g soli.',
                    'Kupus sa soli mijesi rukama 5 do 10 minuta, dok ne pusti puno soka. Slaži ga u staklenke ili keramičku posudu i čvrsto nabijaj šakom, da sok izađe na površinu i prekrije kupus. Između slojeva stavi lovor i papar.',
                    'Na vrh stavi cijeli list kupusa i uteg (manju staklenku s vodom ili čisti kamen), da kupus stalno bude ispod soka. Staklenke ne zatvaraj čvrsto, jer kupus vrije i stvara plin.',
                    'Drži ga na 18–20 °C. Prvih dana nekoliko puta probodi kupus drvenim štapićem do dna, da izađe plin. Nakon 2 do 3 tjedna kupus je kiseo i tada ga premjesti na hladno, gdje se čuva cijelu zimu.',
                    'Glavice za sarmu: iz svake glavice izreži košćak u obliku stošca i rupu napuni solju. Glavice složi u čistu bačvu ili kantu, otvorom prema gore, i između njih stavi lovor, papar, hren i kukuruz.',
                    'Prelij ih ohlađenom salamurom (50 do 60 g soli na litru vode) da ih potpuno prekrije, pa ih pritisni daskom i utegom. Prva 2 tjedna svaka 2 do 3 dana „pretoči” kupus: odlij dio salamure s dna i prelij je odozgo, da se kupus ravnomjerno kiseli i ne dobije neugodan miris.',
                    'Na 15–18 °C glavice su gotove za 4 do 6 tjedana, kad listovi postanu žućkasti, mekani i kiselkasti. Ako je kupus preslan, listove prije sarme kratko isperi ili namoči u hladnoj vodi.',
                ],
            ],
        ];
    }
};
