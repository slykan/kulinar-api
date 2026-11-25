<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Javni (server-rendered) dio kulinar.app — recepti, kalkulatori, vodiči.
 * Flutter web aplikacija živi na /app, mobilne aplikacije koriste samo /api.
 */
class SiteController extends Controller
{
    /** Recept kraći od ovoga (upute + sastojci) ne indeksira se — premalo sadržaja za tražilice. */
    public const MIN_INDEX_CHARS = 600; // kraći korisnički recepti su noindex, bez oglasa i izvan sitemapa

    public const KALKULATORI = [
        'kobasice' => ['naziv' => 'Kalkulator začina za kobasice', 'ico' => '🌭', 'opis' => 'Upiši kilograme mesa i dobiješ točne grame soli, paprike, papra, češnjaka i šećera.', 'kolinje' => true],
        'svinja' => ['naziv' => 'Koliko mesa daje svinja', 'ico' => '🐖', 'opis' => 'Upiši težinu žive svinje i vidi koliko kg buta, lopatica, slanine, masti i mesa za kobasice možeš očekivati.', 'kolinje' => true],
        'soljenje' => ['naziv' => 'Soljenje šunke, pancete i slanine', 'ico' => '🥓', 'opis' => 'Koliko soli, nitritne soli i začina za suho soljenje ili salamuru i koliko dana meso stoji u soli.', 'kolinje' => true],
        'mast-cvarci' => ['naziv' => 'Kalkulator masti i čvaraka', 'ico' => '🔥', 'opis' => 'Koliko masti i čvaraka daje slanina, koliko staklenki pripremiti i koliko soli ide u čvarke.', 'kolinje' => true],
        'kulen' => ['naziv' => 'Kalkulator za kulen', 'ico' => '🌶️', 'opis' => 'Sol, ljuta i slatka paprika i češnjak za kulen i kulenovu seku, preračunato na tvoju količinu mesa.', 'kolinje' => true],
        'susenje' => ['naziv' => 'Kalkulator sušenja mesa', 'ico' => '⚖️', 'opis' => 'Koliko kobasica, kulen, šunka ili panceta trebaju izgubiti na težini da bi bili gotovi.', 'kolinje' => true],
        'crijeva' => ['naziv' => 'Koliko crijeva treba', 'ico' => '📏', 'opis' => 'Metri crijeva za kobasice, krvavice, češnjovke i kulen prema kilogramima nadjeva.', 'kolinje' => true],
        'tripice' => ['naziv' => 'Kalkulator tripica', 'ico' => '🍲', 'opis' => 'Klasični recept za tripice preračunat za bilo koju količinu — od jedne porcije do kotlića.'],
        'tursija' => ['naziv' => 'Kalkulator salamure za turšiju', 'ico' => '🥒', 'opis' => 'Koliko octa, vode, soli i šećera treba za tvoju količinu turšije.'],
    ];

    public static function vodici(): array
    {
        return [
            [
                'slug' => 'advent-badnjak-bozic-stara-godina-jelovnik',
                'title' => 'Advent, Badnjak, Božić i Stara godina: jelovnik i plan kuhanja',
                'excerpt' => 'Što se tradicionalno jede na Badnjak, za Božić i na Staru godinu, koja jela možeš pripremiti danima unaprijed i kako rasporediti kuhanje da blagdane ne provedeš u kuhinji.',
                'date' => '2026-11-25',
                'read' => '7 min',
            ],
            [
                'slug' => 'kolinje-krvavice-svargla-pecenice',
                'title' => 'Krvavice, švargla i pečenice: kolinje korak po korak',
                'excerpt' => 'Što se radi od kojeg dijela svinje, redoslijed poslova na dan kolinja, nadjev za krvavice, kuhanje švargle i kako sve ohladiti i spremiti.',
                'date' => '2026-11-16',
                'read' => '8 min',
                'kolinje' => true,
            ],
            [
                'slug' => 'dimljenje-mesa-drvo-trajanje',
                'title' => 'Dimljenje mesa: koje drvo, koliko dana i hladni ili topli dim',
                'excerpt' => 'Kada meso ide u pušnicu, koje drvo koristiti, a koje nikako, koliko dugo dimiti kobasice, slaninu i šunku i kako izbjeći gorak ili čađav okus.',
                'date' => '2026-11-16',
                'read' => '6 min',
                'kolinje' => true,
            ],
            [
                'slug' => 'susenje-i-cuvanje-suhog-mesa',
                'title' => 'Sušenje i čuvanje suhog mesa: temperatura, vlaga i plijesan',
                'excerpt' => 'Idealni uvjeti za sušenje kobasica, kulena i šunke, kako prepoznati „zatvorenu koru”, koja je plijesan dobra, a koja nije i kako čuvati gotovo suho meso.',
                'date' => '2026-11-16',
                'read' => '7 min',
                'kolinje' => true,
            ],
            [
                'slug' => 'svinjska-crijeva-ciscenje-priprema',
                'title' => 'Svinjska crijeva: čišćenje, priprema i koja za što',
                'excerpt' => 'Tanka, debela i slijepo crijevo, kupljena ili domaća, kako ih očistiti, namočiti i čuvati te koji promjer ide za kobasice, krvavice i kulen.',
                'date' => '2026-11-16',
                'read' => '5 min',
                'kolinje' => true,
            ],
            [
                'slug' => 'domaca-mast-i-cvarci',
                'title' => 'Domaća mast i čvarci: topljenje u kotlu korak po korak',
                'excerpt' => 'Koja slanina za mast, a koja za čvarke, koliko vode na početku, kako znati da su čvarci gotovi, prešanje, soljenje i spremanje masti.',
                'date' => '2026-11-16',
                'read' => '6 min',
                'kolinje' => true,
            ],
            [
                'slug' => 'temperatura-pecenja-mesa',
                'title' => 'Temperatura pečenja mesa: kada je piletina, svinjetina i junetina gotova',
                'excerpt' => 'Sigurne unutarnje temperature za perad, mljeveno meso, svinjetinu, junetinu i ribu, stupnjevi pečenosti bifteka, kako mjeriti termometrom i zašto meso treba odmoriti.',
                'date' => '2026-10-26',
                'read' => '5 min',
            ],
            [
                'slug' => 'koliko-hrana-traje-hladnjak-zamrzivac',
                'title' => 'Koliko hrana traje u hladnjaku i zamrzivaču',
                'excerpt' => 'Rokovi za sirovo meso, ribu, kuhana jela i ostatke u hladnjaku i zamrzivaču, pravilo dva sata, sigurno odmrzavanje i kako čuvati kuhana jela.',
                'date' => '2026-10-26',
                'read' => '5 min',
            ],
            [
                'slug' => 'zimnica-sterilizacija-pasterizacija-staklenki',
                'title' => 'Zimnica: sterilizacija i pasterizacija staklenki korak po korak',
                'excerpt' => 'Kako pripremiti staklenke, koliko ih puniti, kako pasterizirati u vodenoj kupelji, kako provjeriti je li staklenka zatvorena i zašto nekiselo povrće ne ide u lonac.',
                'date' => '2026-10-26',
                'read' => '6 min',
            ],
            [
                'slug' => 'pekmez-i-dzem-omjer-secera',
                'title' => 'Pekmez i džem: koliko šećera na kilogram voća',
                'excerpt' => 'Razlika između pekmeza, džema i marmelade, klasični omjer šećera, želirni šećer 1:1, 2:1 i 3:1, pekmez od šljiva bez šećera i proba na hladnom tanjuru.',
                'date' => '2026-10-26',
                'read' => '5 min',
            ],
            [
                'slug' => 'kiseli-kupus-ribanac-omjer-soli',
                'title' => 'Kiseli kupus (ribanac): koliko soli i kako ga ukiseliti',
                'excerpt' => 'Omjer od 2–2,5 % soli s tablicom za 1 do 25 kg, posuda i uteg, temperatura fermentacije, kada je kupus gotov i po čemu se vidi da nešto nije u redu.',
                'date' => '2026-10-26',
                'read' => '5 min',
            ],
            [
                'slug' => 'domaci-kruh-omjeri-brasna-vode-kvasca',
                'title' => 'Domaći kruh: omjer brašna, vode, kvasca i soli',
                'excerpt' => 'Pekarski postoci, koliko vode za koji kruh, svježi ili suhi kvasac, mijesenje, dizanje i pečenje s parom, najčešće pogreške i kruh s kiselim tijestom.',
                'date' => '2026-10-26',
                'read' => '6 min',
            ],
            [
                'slug' => 'domace-kobasice-za-pecenje',
                'title' => 'Domaće kobasice za pečenje: meso, začini i punjenje korak po korak',
                'excerpt' => 'Koje meso odabrati, koliko masnoće, koliko soli i začina po kilogramu, kako puniti crijeva i koliko svježe kobasice traju.',
                'date' => '2026-10-13',
                'read' => '7 min',
                'kolinje' => true,
            ],
            [
                'slug' => 'tursija-salamura-omjeri',
                'title' => 'Turšija: omjer octa i vode, salamura i pasterizacija tegli',
                'excerpt' => 'Klasična salamura 1:3, koliko soli i šećera, koje povrće ide u turšiju i kako zatvoriti tegle da turšija izdrži do proljeća.',
                'date' => '2026-10-13',
                'read' => '6 min',
            ],
            [
                'slug' => 'tripice-tradicionalni-recept',
                'title' => 'Tripice na tradicionalni način: čišćenje, kuhanje i umak',
                'excerpt' => 'Kako očistiti i skuhati tripice da budu mekane, bez neugodnog mirisa, i kako napraviti gusti umak s paprikom i rajčicom.',
                'date' => '2026-10-13',
                'read' => '6 min',
            ],
            [
                'slug' => 'kuhinjske-mjere-zlica-salica-preracunavanje',
                'title' => 'Kuhinjske mjere: žlica, žličica, šalica — i kako preračunati recept',
                'excerpt' => 'Koliko je grama u žlici soli ili brašna, što znači dkg i kako recept za 4 osobe pretvoriti u recept za 10, bez pogađanja.',
                'date' => '2026-10-13',
                'read' => '5 min',
            ],
        ];
    }

    public function home(): View
    {
        return view('site.home', [
            'recepti' => $this->withRatings(Post::with('user')->where('published', true)->latest('published_at')->take(6)->get()),
            'vodici' => self::vodici(),
            'kalkulatori' => self::KALKULATORI,
            'brojRecepata' => Post::where('published', true)->count(),
        ]);
    }

    public function recepti(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $posts = Post::with('user')->where('published', true)
            ->when($q !== '', fn ($w) => $w->where('title', 'like', '%'.$q.'%'))
            ->latest('published_at')
            ->paginate(24)
            ->withQueryString();

        $this->withRatings($posts->getCollection());

        return view('site.recepti', ['posts' => $posts, 'q' => $q]);
    }

    public function recept(string $slug): View
    {
        $post = Post::with('user')->where('published', true)->where('slug', $slug)->firstOrFail();
        $this->withRatings(collect([$post]));

        $koraci = collect(preg_split('/\r?\n+/', (string) $post->content))
            ->map(fn ($l) => trim(preg_replace('/^\s*\d+[\.\)]\s*/', '', $l)))
            ->filter()
            ->values();

        $sastojci = collect($post->ingredients ?? [])
            ->filter(fn ($s) => trim($s['name'] ?? '') !== '')
            ->map(fn ($s) => trim(implode(' ', array_filter([trim($s['quantity'] ?? ''), trim($s['unit'] ?? ''), trim($s['name'])]))))
            ->values();

        $duljina = self::duljina($post);

        $slicni = $this->withRatings(Post::with('user')->where('published', true)->where('id', '!=', $post->id)
            ->latest('published_at')->take(3)->get());

        return view('site.recept', [
            'post' => $post,
            'koraci' => $koraci,
            'sastojci' => $sastojci,
            'indeksiraj' => $duljina >= self::MIN_INDEX_CHARS,
            'slicni' => $slicni,
        ]);
    }

    public function kalkulatori(): View
    {
        return view('site.kalkulatori', ['kalkulatori' => self::KALKULATORI]);
    }

    public function kalkulator(string $slug): View
    {
        abort_unless(isset(self::KALKULATORI[$slug]), 404);

        return view('site.kalkulator-'.$slug, [
            'k' => self::KALKULATORI[$slug],
            'ostali' => array_diff_key(self::KALKULATORI, [$slug => true]),
        ]);
    }

    /** Recepti koji se vežu uz kolinje (slugovi uredničkih recepata). */
    public const RECEPTI_KOLINJE = ['krvavice-domace', 'svargla-tlacenka', 'cvarci-domaci', 'pogacice-sa-cvarcima', 'pecene-kobasice-s-kiselim-kupusom', 'sarma-od-kiselog-kupusa', 'grah-varivo-s-kobasicom'];

    /** Kolinje: sve na jednom mjestu — kalkulatori, vodiči i recepti. */
    public function kolinje(): View
    {
        $recepti = Post::with('user')->where('published', true)->whereIn('slug', self::RECEPTI_KOLINJE)->get()
            ->sortBy(fn (Post $p) => array_search($p->slug, self::RECEPTI_KOLINJE))->values();

        return view('site.kolinje', [
            'kalkulatori' => array_filter(self::KALKULATORI, fn ($k) => $k['kolinje'] ?? false),
            'vodici' => array_values(array_filter(self::vodici(), fn ($v) => $v['kolinje'] ?? false)),
            'recepti' => $this->withRatings($recepti),
        ]);
    }

    public function vodiciIndex(): View
    {
        return view('site.vodici', ['vodici' => self::vodici()]);
    }

    public function vodic(string $slug): View
    {
        $v = collect(self::vodici())->firstWhere('slug', $slug);
        abort_unless($v && view()->exists('site.vodici.'.$slug), 404);

        return view('site.vodic', [
            'v' => $v,
            'ostali' => collect(self::vodici())->where('slug', '!=', $slug)->values()->all(),
        ]);
    }

    public function sitemap(): Response
    {
        $urls = [
            [url('/'), '1.0', null],
            [url('/recepti'), '0.9', null],
            [url('/kalkulatori'), '0.9', null],
            [url('/vodici'), '0.8', null],
            [url('/kolinje'), '0.9', '2026-11-16'],
        ];
        foreach (array_keys(self::KALKULATORI) as $k) {
            $urls[] = [url('/kalkulatori/'.$k), '0.9', null];
        }
        foreach (self::vodici() as $v) {
            $urls[] = [url('/vodici/'.$v['slug']), '0.8', $v['date']];
        }
        foreach (Post::where('published', true)->latest('published_at')->get(['slug', 'content', 'ingredients', 'updated_at']) as $p) {
            $duljina = self::duljina($p);
            if ($duljina >= self::MIN_INDEX_CHARS) {
                $urls[] = [url('/recepti/'.$p->slug), '0.7', $p->updated_at?->toDateString()];
            }
        }
        foreach (['o-nama', 'kontakt', 'privatnost'] as $s) {
            $urls[] = [url('/'.$s), '0.3', null];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as [$loc, $prio, $mod]) {
            $xml .= '  <url><loc>'.e($loc).'</loc>'.($mod ? '<lastmod>'.$mod.'</lastmod>' : '').'<priority>'.$prio.'</priority></url>'."\n";
        }

        return response($xml.'</urlset>'."\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** Prosječna ocjena i broj ocjena (tablica ratings možda još ne postoji na starijim instalacijama). */
    private function withRatings($posts)
    {
        $ids = collect($posts)->pluck('id');
        $data = collect();
        try {
            $data = DB::table('ratings')->whereIn('post_id', $ids)->groupBy('post_id')
                ->selectRaw('post_id, AVG(rating) as avg_rating, COUNT(*) as cnt')->get()->keyBy('post_id');
        } catch (\Throwable) {
        }
        foreach ($posts as $p) {
            $p->rating_average = isset($data[$p->id]) ? round($data[$p->id]->avg_rating, 1) : 0;
            $p->rating_count = isset($data[$p->id]) ? (int) $data[$p->id]->cnt : 0;
        }

        return $posts;
    }

    /** Broj znakova sadržaja recepta (upute + sastojci) — mjera je li recept dovoljno opširan za indeksiranje. */
    public static function duljina(Post $p): int
    {
        $sastojci = collect($p->ingredients ?? [])->map(fn ($s) => implode(' ', array_filter([$s['quantity'] ?? '', $s['unit'] ?? '', $s['name'] ?? ''])))->implode(' ');

        return mb_strlen((string) $p->content) + mb_strlen($sastojci);
    }

    /** „4 osobe", „6 osoba" — s $za: „za 1 osobu", „za 4 osobe", „za 6 osoba". */
    public static function osobe(int $n, bool $za = false): string
    {
        $zadnja = $n % 10;
        $oblik = ($n % 100 >= 11 && $n % 100 <= 14) ? 'osoba'
            : ($zadnja === 1 ? ($za ? 'osobu' : 'osoba') : ($zadnja >= 2 && $zadnja <= 4 ? 'osobe' : 'osoba'));

        return ($za ? 'za ' : '').$n.' '.$oblik;
    }

    /** Potpis ispod slike s Wikimedia Commonsa: „Foto: autor, licenca (Wikimedia Commons)” ili null ako slika nema upisan izvor. */
    public static function izvorSlike(?string $path): ?string
    {
        $i = $path ? (config('slike') ?? [])[ltrim($path, '/')] ?? null : null;
        if (! $i) {
            return null;
        }
        $licenca = $i['licenca_url'] ? '<a href="'.e($i['licenca_url']).'" target="_blank" rel="noopener nofollow">'.e($i['licenca']).'</a>' : e($i['licenca']);

        return 'Foto: '.e($i['autor']).', '.$licenca.', <a href="'.e($i['izvor']).'" target="_blank" rel="noopener nofollow">'.e($i['platforma'] ?? 'Wikimedia Commons').'</a>';
    }

    /** Slika s public diska (storage) s potpisom autora. */
    public static function slikaSIzvorom(string $path, string $alt): string
    {
        return '<figure class="slika-izvor"><img src="'.e(self::slika($path)).'" alt="'.e($alt).'" loading="lazy">'
            .(($izvor = self::izvorSlike($path)) ? '<figcaption>'.$izvor.'</figcaption>' : '').'</figure>';
    }

    public static function slika(?string $path): ?string
    {
        return $path ? url('/storage/'.ltrim($path, '/')) : null;
    }

    public static function sazetak(Post $p, int $n = 140): string
    {
        $t = trim((string) ($p->excerpt ?: preg_replace('/\s+/', ' ', preg_replace('/^\s*\d+[\.\)]\s*/m', '', (string) $p->content))));

        return Str::limit($t, $n);
    }
}
