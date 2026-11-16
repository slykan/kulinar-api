@extends('site.layout', [
    'title' => 'Koliko mesa daje svinja — kalkulator randmana za kolinje',
    'description' => 'Upiši težinu žive svinje i kalkulator procijeni polovice, butove, lopatice, slaninu, mast, čvarke, krv i meso za kobasice. Npr. svinja od 150 kg daje oko 117 kg polovica.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kolinje">Kolinje</a> › Koliko mesa daje svinja</div>
    <h1 class="title">🐖 Koliko mesa daje svinja</h1>
    <p class="lead">Upiši težinu žive svinje, a kalkulator procijeni koliko ćeš dobiti polovica, buta, lopatica, slanine, masti i mesa za kobasice. Tako na vrijeme znaš koliko začina, crijeva i staklenki treba.</p>

    <div class="tool">
        <div class="red2">
            <div>
                <label for="kg">Težina žive svinje</label>
                <input id="kg" type="number" min="40" step="5" value="150" inputmode="decimal"> <span style="color:var(--muted);margin-left:6px;">kg</span>
            </div>
            <div>
                <label for="tip">Kakva je svinja</label>
                <select id="tip">
                    <option value="domaca">Domaća, masnija (tovljena kukuruzom)</option>
                    <option value="mesnata">Mesnata pasmina (iz tova)</option>
                </select>
            </div>
        </div>
        <div class="hint">Procjena za svinju od 100 do 220 kg. Stvarni prinos ovisi o pasmini, hranidbi i tome kako se meso reže.</div>

        <div class="velike" id="velike"></div>

        <table class="t" style="margin-top:14px;">
            <thead><tr><th>Dio</th><th class="num">Okvirno</th><th>Obično ide u</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <h3 style="margin:18px 0 6px;font-size:1.05rem;">Iznutrice i krv</h3>
        <table class="t"><tbody id="rows2"></tbody></table>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši procjenu</button>
    </div>

    <div class="prose">
        <h2>Kako kalkulator računa</h2>
        <p>
            <strong>Randman</strong> je udio polovica (trupa s glavom, bez krvi, iznutrica i dlake) u težini žive svinje.
            Kod domaćeg klanja to je obično <strong>76–80 %</strong>: svinja od 150 kg daje oko 115–120 kg polovica. Kalkulator računa
            s 78 %. Što je svinja teža i masnija, randman je nešto veći.
        </p>
        <p>
            Polovice se zatim dijele na dijelove prema uobičajenom udjelu. Domaća, kukuruzom tovljena svinja ima više leđne slanine
            i sala, a mesnata pasmina više buta, lopatice i leđa. Zato prinos masti kod domaće svinje bude i dvostruko veći.
        </p>

        <h2>Primjer: svinja od 150 kg (domaća)</h2>
        <table class="t">
            <tr><th>Što</th><th class="num">Okvirno</th></tr>
            <tr><td>Polovice</td><td class="num">117 kg</td></tr>
            <tr><td>Dva buta</td><td class="num">24,5 kg</td></tr>
            <tr><td>Dvije lopatice</td><td class="num">14 kg</td></tr>
            <tr><td>Leđna slanina</td><td class="num">16,5 kg</td></tr>
            <tr><td>Mast nakon topljenja</td><td class="num">oko 14 L</td></tr>
            <tr><td>Krv</td><td class="num">oko 5 L</td></tr>
        </table>

        <h2>Što od čega</h2>
        <ul>
            <li><strong>But</strong> — šunka ili pršut za sušenje, a ako ga ne sušiš, odresci, pečenje ili meso za kobasice. Za soli i dane soljenja vidi <a href="/kalkulatori/soljenje">kalkulator soljenja</a>.</li>
            <li><strong>Lopatica i obrezci</strong> — glavno meso za kobasice i kulen (<a href="/kalkulatori/kobasice">začini za kobasice</a>, <a href="/kalkulatori/kulen">kalkulator za kulen</a>).</li>
            <li><strong>Leđa i vrat</strong> — pečenica (fino meso iz leđa), kotleti, vratina za sušenje.</li>
            <li><strong>Potrbušina i rebra</strong> — panceta, suha rebra, dio za kobasice.</li>
            <li><strong>Leđna slanina i salo</strong> — soljena slanina, mast i čvarci (<a href="/kalkulatori/mast-cvarci">kalkulator masti i čvaraka</a>).</li>
            <li><strong>Glava, nogice, koža, jezik, srce</strong> — švargla, hladetina, krvavice (<a href="/vodici/kolinje-krvavice-svargla-pecenice">krvavice, švargla i pečenice</a>).</li>
        </ul>

        <h2>Česta pitanja</h2>
        <h3>Koliko mesa za kobasice daje svinja od 150 kg?</h3>
        <p>
            Ako butove sušiš, za kobasice obično ostaju lopatice, obrezci i dio potrbušine — okvirno 20–25 kg mesa sa slaninom, što je
            25–30 kg kobasica nakon dodavanja začina i punjenja. Ako i but ide u kobasice, računaj s još 20-ak kg.
        </p>
        <h3>Koliko krvi daje svinja?</h3>
        <p>
            Kod klanja se iscijedi oko 3–3,5 % težine žive svinje, što je za svinju od 150 kg oko 4,5–5 litara. Krv treba odmah miješati
            (ili dodati malo soli) da se ne zgruša ako ide u krvavice.
        </p>
        <h3>Koliko dobijem masti?</h3>
        <p>
            Od leđne slanine i sala dobije se oko 60–70 % masti i 15–20 % čvaraka. Domaća svinja od 150 kg obično daje 10–15 litara masti.
            Točnije izračunaj u <a href="/kalkulatori/mast-cvarci">kalkulatoru masti i čvaraka</a>.
        </p>
        <p>Sve o kolinju na jednom mjestu: <a href="/kolinje">Kolinje — kalkulatori, vodiči i recepti</a>.</p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<style>
    .red2 { display: grid; grid-template-columns: 1fr 1.4fr; gap: 18px; }
    .red2 select { max-width: 100%; }
    .red2 input { max-width: 160px; }
    .velike { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 22px; }
    .velike div { background: var(--bg); border: 1px solid var(--line); border-radius: 14px; padding: 14px 16px; }
    .velike b { display: block; font-size: 1.5rem; font-weight: 850; color: #fff; font-variant-numeric: tabular-nums; }
    .velike span { color: var(--muted); font-size: .85rem; }
    @media (max-width: 760px) { .red2, .velike { grid-template-columns: 1fr; } }
</style>
<script>
(function () {
    // udio u polovicama (%): [dio, domaća, mesnata, kamo ide]
    var D = [
        ['Butovi (2 kom)', 21, 25, 'šunka, pršut, pečenje'],
        ['Lopatice (2 kom)', 12, 14, 'kobasice, kulen, gulaš'],
        ['Leđa (kare) i pečenica', 11, 14, 'kotleti, pečenica, sušena pečenica'],
        ['Vrat', 5, 6, 'vratina, kobasice, roštilj'],
        ['Potrbušina s rebrima', 13, 12, 'panceta, suha rebra, kobasice'],
        ['Obrezci (sitno meso)', 5, 4, 'kobasice'],
        ['Leđna slanina', 14, 8, 'slanina, mast, čvarci'],
        ['Salo (unutarnja mast)', 3, 2, 'mast'],
        ['Glava', 6, 6, 'švargla, hladetina'],
        ['Nogice', 2, 2, 'hladetina, juha'],
        ['Kosti', 8, 7, 'juha, kuhanje']
    ];
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 1 });
    function kg(x) { return nf.format(Math.round(x * 2) / 2) + ' kg'; }
    function calc() {
        var ziva = Math.max(0, parseFloat(String(document.getElementById('kg').value).replace(',', '.')) || 0),
            dom = document.getElementById('tip').value === 'domaca',
            pol = ziva * 0.78, h = '', mastKg = 0, cvarci = 0;
        D.forEach(function (d) {
            var m = pol * (dom ? d[1] : d[2]) / 100;
            if (d[0].indexOf('slanina') >= 0 || d[0].indexOf('Salo') >= 0) { mastKg += m * 0.65; cvarci += m * 0.17; }
            h += '<tr><td>' + d[0] + '</td><td class="num"><strong>' + kg(m) + '</strong></td><td style="color:var(--muted)">' + d[3] + '</td></tr>';
        });
        document.getElementById('rows').innerHTML = h;
        document.getElementById('velike').innerHTML =
            '<div><b>' + kg(pol) + '</b><span>polovice (78 %)</span></div>' +
            '<div><b>~' + nf.format(Math.round(mastKg / 0.9)) + ' L</b><span>masti nakon topljenja</span></div>' +
            '<div><b>~' + kg(cvarci) + '</b><span>čvaraka</span></div>';
        document.getElementById('rows2').innerHTML =
            '<tr><td>Krv (3–3,5 % žive težine)</td><td class="num"><strong>' + nf.format(Math.round(ziva * 0.032 * 2) / 2) + ' L</strong></td></tr>' +
            '<tr><td>Jetra</td><td class="num">' + kg(ziva * 0.014) + '</td></tr>' +
            '<tr><td>Srce, pluća, bubrezi, jezik</td><td class="num">' + kg(ziva * 0.017) + '</td></tr>' +
            '<tr><td>Tanka crijeva (za kobasice)</td><td class="num">oko ' + Math.round(ziva * 0.12) + ' m</td></tr>';
    }
    document.getElementById('kg').addEventListener('input', calc);
    document.getElementById('tip').addEventListener('change', calc);
    calc();
})();
</script>
@endpush
