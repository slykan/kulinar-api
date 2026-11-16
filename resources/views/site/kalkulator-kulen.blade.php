@extends('site.layout', [
    'title' => 'Kalkulator za kulen — sol, paprika i češnjak po kg mesa',
    'description' => 'Koliko soli, ljute i slatke paprike i češnjaka ide u kulen i kulenovu seku? Upiši kilograme mesa i odaberi ljutinu. Npr. 10 kg mesa: 200 g soli, 300 g paprike, 40 g češnjaka.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kolinje">Kolinje</a> › Kulen</div>
    <h1 class="title">🌶️ Kalkulator za kulen</h1>
    <p class="lead">Kulen je ponos svakog kolinja i u njega idu samo meso, sol, paprika i češnjak, zato svaki gram ima težinu. Upiši koliko mesa imaš i koliko ljut voliš, a kalkulator izračuna sve začine i koliko će kulena ostati nakon sušenja.</p>

    <div class="tool">
        <div class="red2">
            <div>
                <label for="kg">Meso za kulen (sa slaninom)</label>
                <input id="kg" type="number" min="0.5" step="0.5" value="10" inputmode="decimal"> <span style="color:var(--muted);margin-left:6px;">kg</span>
            </div>
            <div>
                <label for="ljuto">Ljutina</label>
                <select id="ljuto">
                    <option value="blago">Blaži (2/3 slatke paprike)</option>
                    <option value="pola" selected>Klasični (pola-pola)</option>
                    <option value="ljuto">Ljući (2/3 ljute paprike)</option>
                </select>
            </div>
        </div>
        <div class="hint">Upisuje se ukupna masa samljevenog mesa i tvrde slanine, bez crijeva.</div>
        <table class="t" style="margin-top:22px;">
            <thead><tr><th>Sastojak</th><th class="num">% mase</th><th class="num">Količina</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <div class="note" id="dodatno" style="margin:0 0 16px;"></div>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši popis</button>
    </div>

    <div class="prose">
        {!! \App\Http\Controllers\SiteController::slikaSIzvorom('kolinje/kulen.jpg', 'Domaći kulen na dasci') !!}
        <h2>Meso za kulen</h2>
        <p>
            Za kulen se uzima <strong>najkvalitetnije, čvrsto meso</strong>: but, lopatica i leđa, bez tetiva i krvavih dijelova.
            Slanine je malo, svega 10–15 % i to tvrde leđne. Meso treba biti dobro ohlađeno (0–2 °C) i melje se krupno, na rešetku od
            8–10 mm, ili se dio sjecka nožem.
        </p>

        <h2>Odakle ovi omjeri</h2>
        <ul>
            <li><strong>Sol 2 %</strong> — pola nitritne, pola morske soli. Kulen se suši mjesecima, pa sol i nitrit štite meso dok se ne osuši.</li>
            <li><strong>Paprika 3 %</strong> — mljevena začinska paprika, slatka i ljuta. Ona kulenu daje boju i okus, pa uzmi najbolju koju možeš nabaviti, svježe mljevenu.</li>
            <li><strong>Češnjak 0,4 %</strong> — svježi, protisnut ili sitno nasjeckan. Neki ga stavljaju u obliku „vode od češnjaka” (namočen i procijeđen).</li>
            <li><strong>Šećer 0,2 %</strong> — po želji, pomaže zrenju. Tradicionalni slavonski kulen ga nema.</li>
        </ul>

        <div class="warn">
            <strong>Kulen je fermentirana i sušena kobasica</strong> koja se ne kuha, pa je osjetljiviji od kobasica za pečenje. Radi ga s
            nitritnom soli, punu kulenovku drži prvih dana na 10–15 °C da se zrenje polako pokrene, a zatim suši na 12–16 °C uz vlagu
            75–85 %. Sušenje traje 4–6 mjeseci. Više u vodiču <a href="/vodici/susenje-i-cuvanje-suhog-mesa">sušenje i čuvanje suhog mesa</a>.
        </div>

        <h2>Postupak u kratko</h2>
        <ol>
            <li>Meso i slaninu ohladi gotovo do smrzavanja i sameljaj krupno.</li>
            <li>Začine izmiješaj zasebno, pospi po mesu i mijesi dok smjesa ne postane ljepljiva i ujednačene boje.</li>
            <li>Ostavi smjesu preko noći na hladnom (2–4 °C) da se začini prožmu, pa je još jednom kratko promiješaj.</li>
            <li>Puni čvrsto u slijepo crijevo (kulen) ili debelo crijevo (kulenova seka), bez zraka. Izbockaj mjehuriće iglom i čvrsto poveži.</li>
            <li>Nakon nekoliko dana cijeđenja kulen ide na hladni dim, pa na dugo sušenje.</li>
        </ol>

        <h2>Kulen ili kulenova seka?</h2>
        <p>
            Isti nadjev, različito crijevo. <strong>Kulen</strong> se puni u slijepo crijevo, pa je debeo, nepravilnog oblika i težak
            1–2 kg, a suši se najmanje 5–6 mjeseci. <strong>Kulenova seka</strong> se puni u debelo crijevo, tanja je i gotova je ranije,
            za 2–3 mjeseca. Koliko crijeva treba izračunaj u <a href="/kalkulatori/crijeva">kalkulatoru crijeva</a>.
        </p>

        <h2>Česta pitanja</h2>
        <h3>Koliko kulena ostane nakon sušenja?</h3>
        <p>Kulen izgubi 35–40 % težine. Od 10 kg nadjeva ostane oko 6–6,5 kg gotovog kulena. Prati gubitak u <a href="/kalkulatori/susenje">kalkulatoru sušenja</a>.</p>
        <h3>Zašto mi je kulen šupalj ili siv u sredini?</h3>
        <p>
            Siva i šuplja sredina najčešće znače da je u crijevu ostalo zraka ili da se kulen presušio izvana pa se kora zatvorila prije
            nego što se osušila sredina. Puni čvrsto i ne suši na propuhu ni na toplom.
        </p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<style>
    .red2 { display: grid; grid-template-columns: 1fr 1.4fr; gap: 18px; }
    .red2 select { max-width: 100%; }
    .red2 input { max-width: 160px; }
    @media (max-width: 760px) { .red2 { grid-template-columns: 1fr; } }
</style>
<script>
(function () {
    var L = { blago: [2, 1], pola: [1.5, 1.5], ljuto: [1, 2] };
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 2 });
    function fmt(g) { return g >= 1000 ? nf.format(Math.round(g / 10) / 100) + ' kg' : Math.round(g) + ' g'; }
    function calc() {
        var kg = Math.max(0, parseFloat(String(document.getElementById('kg').value).replace(',', '.')) || 0),
            p = L[document.getElementById('ljuto').value], sum = 0, h = '';
        [['Morska sol', 1], ['Nitritna sol', 1], ['Slatka paprika', p[0]], ['Ljuta paprika', p[1]], ['Češnjak (svježi)', 0.4], ['Šećer (po želji)', 0.2]].forEach(function (z) {
            var g = kg * 10 * z[1]; sum += g;
            h += '<tr><td>' + z[0] + '</td><td class="num">' + nf.format(z[1]) + ' %</td><td class="num"><strong>' + fmt(g) + '</strong></td></tr>';
        });
        h += '<tr class="total"><td>Ukupno začina</td><td class="num"></td><td class="num">' + fmt(sum) + '</td></tr>';
        document.getElementById('rows').innerHTML = h;
        document.getElementById('dodatno').innerHTML = 'Meso: <strong>' + nf.format(Math.round(kg * 8.7) / 10) + ' kg</strong> čistog mesa i <strong>' +
            nf.format(Math.round(kg * 1.3) / 10) + ' kg</strong> tvrde slanine (13 %). Nakon sušenja ostane oko <strong>' +
            nf.format(Math.round(kg * 6.2) / 10) + ' kg</strong> kulena. To je otprilike ' + Math.max(1, Math.round(kg / 1.5)) + ' kulena u slijepom crijevu ili ' +
            nf.format(Math.round(kg / 1.8 * 10) / 10) + ' m debelog crijeva za seku.';
    }
    document.getElementById('kg').addEventListener('input', calc);
    document.getElementById('ljuto').addEventListener('change', calc);
    calc();
})();
</script>
@endpush
