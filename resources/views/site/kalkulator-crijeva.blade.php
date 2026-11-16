@extends('site.layout', [
    'title' => 'Koliko crijeva treba za kobasice, krvavice i kulen — kalkulator',
    'description' => 'Upiši kilograme nadjeva za kobasice, češnjovke, krvavice, kulenovu seku, kulen i švarglu i kalkulator izračuna metre crijeva i komade. Npr. 10 kg kobasica = oko 11 m tankih crijeva, s rezervom 13 m.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kolinje">Kolinje</a> › Crijeva</div>
    <h1 class="title">📏 Koliko crijeva treba</h1>
    <p class="lead">Upiši koliko kilograma nadjeva planiraš za svaku vrstu, a kalkulator izračuna koliko metara crijeva ili komada treba kupiti, s rezervom za pucanje i krajeve.</p>

    <div class="tool">
        <table class="t unos">
            <thead><tr><th>Što puniš</th><th class="num">Nadjev</th><th class="num">Crijeva</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <label style="display:flex;gap:8px;align-items:center;font-weight:600;color:var(--muted);margin-top:6px;">
            <input type="checkbox" id="rezerva" checked style="width:auto;"> Dodaj 15 % rezerve
        </label>
        <div class="note" id="ukupno" style="margin:16px 0;"></div>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši popis za kupnju</button>
    </div>

    <div class="prose">
        <h2>Koliko stane u metar crijeva</h2>
        <table class="t">
            <tr><th>Crijevo</th><th class="num">Promjer</th><th class="num">Nadjeva po metru</th><th>Za što</th></tr>
            <tr><td>Tanko svinjsko</td><td class="num">28–32 mm</td><td class="num">0,6–0,7 kg</td><td>krvavice, tanje kobasice</td></tr>
            <tr><td>Tanko svinjsko</td><td class="num">32–36 mm</td><td class="num">0,8–1 kg</td><td>kobasice za pečenje, češnjovke</td></tr>
            <tr><td>Debelo svinjsko</td><td class="num">45–55 mm</td><td class="num">1,5–2 kg</td><td>kulenova seka, švargla</td></tr>
            <tr><td>Slijepo crijevo</td><td class="num">—</td><td class="num">1–2 kg po komadu</td><td>kulen</td></tr>
            <tr><td>Svinjski želudac</td><td class="num">—</td><td class="num">1,5–2,5 kg po komadu</td><td>švargla</td></tr>
        </table>
        <p>
            Kupljena prirodna crijeva prodaju se soljena, u svežnjevima ili na metar. Na pakiranju piše promjer (kalibar), npr. 32/34.
            Jedna svinja ima samo jedno slijepo crijevo i jedan želudac, pa ih za više kulena ili švargli treba kupiti.
        </p>

        <h2>Kako pripremiti crijeva</h2>
        <ol>
            <li>Soljena crijeva isperi izvana i namoči u mlakoj vodi 1–2 sata (tanka) ili preko noći (debela).</li>
            <li>Svako crijevo natakni na slavinu i pusti mlaku vodu kroz njega da se ispere sol i provjere rupe.</li>
            <li>Do punjenja ih drži u posudi s mlakom vodom. Neiskorištena dobro posoli i spremi u hladnjak ili zamrzni.</li>
        </ol>
        <p>Domaća crijeva i čišćenje korak po korak: <a href="/vodici/svinjska-crijeva-ciscenje-priprema">svinjska crijeva — čišćenje i priprema</a>.</p>

        <h2>Česta pitanja</h2>
        <h3>Zašto mi crijeva pucaju pri punjenju?</h3>
        <p>
            Najčešće zbog prečvrstog punjenja, presuhih crijeva ili zraka u stroju. Crijeva drži mokrima, puni ravnomjerno i ne do kraja
            — kobasice ćeš ionako zakretati na dijelove, pa treba ostaviti malo prostora.
        </p>
        <h3>Mogu li koristiti umjetna crijeva?</h3>
        <p>
            Kolagena crijeva su zgodna za kobasice za pečenje i sušenje. Za kulen i švarglu se umjesto slijepog crijeva i želuca
            koriste umjetni ovitci odgovarajućeg promjera, ali tradicionalni okus i oblik dobiješ samo s prirodnima.
        </p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<style>
    .unos input { max-width: 110px; padding: 8px 10px; font-size: 1rem; text-align: right; }
    .unos td { vertical-align: middle; }
    .unos small { display: block; color: var(--dim); font-size: .8rem; }
</style>
<script>
(function () {
    // [id, naziv, opis crijeva, kg po metru ili po komadu, 'm' ili 'kom', zadano kg]
    var R = [
        ['kob', 'Kobasice za pečenje', 'tanko 32–36 mm', 0.9, 'm', 10],
        ['ces', 'Češnjovke / suhe kobasice', 'tanko 32–36 mm', 0.9, 'm', 0],
        ['krv', 'Krvavice', 'tanko 28–32 mm', 0.65, 'm', 3],
        ['sek', 'Kulenova seka', 'debelo 45–55 mm', 1.8, 'm', 0],
        ['kul', 'Kulen', 'slijepo crijevo', 1.5, 'kom', 0],
        ['sva', 'Švargla', 'svinjski želudac', 2, 'kom', 0]
    ];
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 1 });
    var h = '';
    R.forEach(function (r) {
        h += '<tr><td>' + r[1] + '<small>' + r[2] + '</small></td><td class="num"><input id="u-' + r[0] + '" type="number" min="0" step="0.5" value="' + r[5] + '" inputmode="decimal"> kg</td>' +
            '<td class="num"><strong id="o-' + r[0] + '"></strong></td></tr>';
    });
    document.getElementById('rows').innerHTML = h;
    function calc() {
        var rez = document.getElementById('rezerva').checked ? 1.15 : 1, tanka = 0, debela = 0, kom = [];
        R.forEach(function (r) {
            var kg = Math.max(0, parseFloat(String(document.getElementById('u-' + r[0]).value).replace(',', '.')) || 0), out;
            if (r[4] === 'm') {
                var m = kg / r[3] * rez;
                out = kg ? nf.format(Math.ceil(m * 2) / 2) + ' m' : '—';
                if (r[3] < 1) tanka += m; else debela += m;
            } else {
                var n = Math.ceil(kg / r[3]);
                out = kg ? n + ' kom' : '—';
                if (n) kom.push(n + ' × ' + (r[0] === 'kul' ? 'slijepo crijevo' : 'želudac'));
            }
            document.getElementById('o-' + r[0]).textContent = out;
        });
        var t = [];
        if (tanka) t.push('<strong>' + nf.format(Math.ceil(tanka)) + ' m</strong> tankih crijeva');
        if (debela) t.push('<strong>' + nf.format(Math.ceil(debela)) + ' m</strong> debelih crijeva');
        t = t.concat(kom.map(function (k) { return '<strong>' + k + '</strong>'; }));
        document.getElementById('ukupno').innerHTML = t.length ? 'Za kupnju: ' + t.join(', ') + '.' : 'Upiši kilograme nadjeva.';
    }
    document.querySelectorAll('.unos input, #rezerva').forEach(function (el) { el.addEventListener('input', calc); el.addEventListener('change', calc); });
    calc();
})();
</script>
@endpush
