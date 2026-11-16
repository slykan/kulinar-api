@extends('site.layout', [
    'title' => 'Kalkulator sušenja mesa — kad su kobasice, kulen i šunka gotovi',
    'description' => 'Koliko težine moraju izgubiti suhe kobasice, kulen, šunka, panceta i slanina da bi bili gotovi? Upiši početnu i trenutnu težinu i vidi koliko je ostalo do kraja sušenja.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kolinje">Kolinje</a> › Sušenje mesa</div>
    <h1 class="title">⚖️ Kalkulator sušenja mesa</h1>
    <p class="lead">Najpouzdaniji znak da je suho meso gotovo nije kalendar nego vaga. Izvaži komad kad ga objesiš, a kalkulator kaže koliko treba težiti kad je gotov. Kad ga kasnije izvažeš, vidiš koliko je posla ostalo.</p>

    <div class="tool">
        <div class="red3">
            <div>
                <label for="vrsta">Što sušiš</label>
                <select id="vrsta">
                    <option value="kobasica">Suha kobasica / češnjovka</option>
                    <option value="kulen">Kulen</option>
                    <option value="seka">Kulenova seka</option>
                    <option value="sunka">Šunka / pršut</option>
                    <option value="panceta">Panceta</option>
                    <option value="vrat">Vratina / pečenica</option>
                    <option value="slanina">Slanina</option>
                </select>
            </div>
            <div>
                <label for="pocetna">Težina na početku</label>
                <input id="pocetna" type="number" min="0.05" step="0.05" value="1" inputmode="decimal"> <span style="color:var(--muted);margin-left:4px;">kg</span>
            </div>
            <div>
                <label for="sada">Težina sada (po želji)</label>
                <input id="sada" type="number" min="0" step="0.01" placeholder="npr. 0,8" inputmode="decimal"> <span style="color:var(--muted);margin-left:4px;">kg</span>
            </div>
        </div>
        <div class="hint">Početna težina je težina nakon punjenja ili soljenja, prije dimljenja. Izvaži uvijek isti komad.</div>

        <div class="velike" id="velike"></div>
        <div class="traka noprint" id="trakaBox" hidden><div id="traka"></div></div>
        <div class="note" id="poruka" style="margin:14px 0 0;"></div>
    </div>

    <div class="prose">
        <h2>Koliko meso treba izgubiti</h2>
        <table class="t">
            <tr><th>Proizvod</th><th class="num">Gubitak težine</th><th class="num">Okvirno trajanje</th></tr>
            <tr><td>Suha kobasica, češnjovka</td><td class="num">30–35 %</td><td class="num">4–8 tjedana</td></tr>
            <tr><td>Kulenova seka</td><td class="num">32–37 %</td><td class="num">2–3 mjeseca</td></tr>
            <tr><td>Kulen</td><td class="num">35–40 %</td><td class="num">5–6 mjeseci</td></tr>
            <tr><td>Šunka / pršut</td><td class="num">30–35 %</td><td class="num">9–18 mjeseci</td></tr>
            <tr><td>Panceta</td><td class="num">25–30 %</td><td class="num">1–3 mjeseca</td></tr>
            <tr><td>Vratina, pečenica</td><td class="num">30–35 %</td><td class="num">1–3 mjeseca</td></tr>
            <tr><td>Slanina</td><td class="num">15–20 %</td><td class="num">1–2 mjeseca</td></tr>
        </table>
        <p>
            Trajanje jako ovisi o debljini komada, temperaturi i vlazi u prostoriji, zato je vaga pouzdanija. Tanka kobasica u suhom
            tavanu bude gotova za tri tjedna, a ista ta kobasica u vlažnom podrumu treba i dva mjeseca.
        </p>

        <h2>Kako pratiti sušenje</h2>
        <ol>
            <li>Izaberi jedan ili dva komada kao „kontrolne” i označi ih koncem u boji.</li>
            <li>Izvaži ih odmah nakon punjenja (ili soljenja) i zapiši težinu i datum.</li>
            <li>Važi ih jednom tjedno. Prvih tjedana gubitak je brz, a pred kraj se jako uspori.</li>
            <li>Kad dosegnu ciljni gubitak, prereži jedan komad: presjek mora biti ujednačene boje i čvrst do sredine.</li>
        </ol>

        <div class="warn">
            Ako meso gubi težinu prebrzo (više od 3–4 % tjedno nakon prvog tjedna), prostorija je presuha ili ima propuha. Kora se tada
            zatvori, sredina ostane vlažna i može se pokvariti. Smanji propuh ili povećaj vlagu — vidi
            <a href="/vodici/susenje-i-cuvanje-suhog-mesa">sušenje i čuvanje suhog mesa</a>.
        </div>

        <h2>Česta pitanja</h2>
        <h3>Kobasice su tvrde izvana, a mekane iznutra. Što sad?</h3>
        <p>
            To je „zatvorena kora”. Kobasice premjesti na hladnije i vlažnije mjesto (npr. podrum) na tjedan-dva da se vlaga izjednači,
            pa nastavi sušenje polaganije. Ubuduće ih ne suši uz peć ili na jakom propuhu.
        </p>
        <h3>Mogu li jesti kobasice prije nego što izgube 30 %?</h3>
        <p>
            Kobasice za sušenje mogu se u svakom trenutku skuhati ili ispeći. Sirove ih jedi tek kad se dovoljno osuše — tada su sol i
            kiselost dovoljno visoki da meso bude sigurno.
        </p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<style>
    .red3 { display: grid; grid-template-columns: 1.3fr 1fr 1fr; gap: 16px 18px; }
    .red3 select { max-width: 100%; }
    .red3 input { max-width: 150px; }
    .velike { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 22px; }
    .velike div { background: var(--bg); border: 1px solid var(--line); border-radius: 14px; padding: 14px 16px; }
    .velike b { display: block; font-size: 1.5rem; font-weight: 850; color: #fff; font-variant-numeric: tabular-nums; }
    .velike span { color: var(--muted); font-size: .85rem; }
    .traka { height: 12px; background: var(--bg); border: 1px solid var(--line); border-radius: 99px; margin-top: 16px; overflow: hidden; }
    .traka div { height: 100%; background: var(--o); border-radius: 99px; transition: width .3s; }
    @media (max-width: 760px) { .red3, .velike { grid-template-columns: 1fr; } }
</style>
<script>
(function () {
    // [min gubitak %, max gubitak %, trajanje]
    var V = { kobasica: [30, 35, '4–8 tjedana'], kulen: [35, 40, '5–6 mjeseci'], seka: [32, 37, '2–3 mjeseca'], sunka: [30, 35, '9–18 mjeseci'],
        panceta: [25, 30, '1–3 mjeseca'], vrat: [30, 35, '1–3 mjeseca'], slanina: [15, 20, '1–2 mjeseca'] };
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 2 });
    function br(id) { return Math.max(0, parseFloat(String(document.getElementById(id).value).replace(',', '.')) || 0); }
    function kg(x) { return x < 1 ? Math.round(x * 1000) + ' g' : nf.format(Math.round(x * 100) / 100) + ' kg'; }
    function calc() {
        var v = V[document.getElementById('vrsta').value], p = br('pocetna'), s = br('sada'),
            cMax = p * (1 - v[0] / 100), cMin = p * (1 - v[1] / 100), poruka;
        document.getElementById('velike').innerHTML =
            '<div><b>' + v[0] + '–' + v[1] + ' %</b><span>potreban gubitak</span></div>' +
            '<div><b>' + kg(cMin) + '–' + kg(cMax) + '</b><span>težina kad je gotovo</span></div>' +
            '<div><b>' + v[2] + '</b><span>okvirno trajanje</span></div>';
        var box = document.getElementById('trakaBox');
        if (s > 0 && p > 0 && s <= p) {
            var izgubio = (1 - s / p) * 100, posto = Math.min(100, izgubio / v[0] * 100);
            box.hidden = false;
            document.getElementById('traka').style.width = posto + '%';
            poruka = 'Izgubljeno <strong>' + nf.format(Math.round(izgubio * 10) / 10) + ' %</strong>. ';
            poruka += izgubio >= v[0] ? '<strong>Gotovo je</strong> — prereži jedan komad i provjeri je li presjek ujednačen do sredine.'
                : 'Još treba izgubiti oko <strong>' + kg(s - cMax) + '</strong> (' + nf.format(Math.round((v[0] - izgubio) * 10) / 10) + ' %).';
        } else {
            box.hidden = true;
            poruka = s > p ? 'Trenutna težina ne može biti veća od početne.' : 'Upiši trenutnu težinu kad izvažeš komad, pa vidiš koliko je ostalo.';
        }
        document.getElementById('poruka').innerHTML = poruka;
    }
    ['pocetna', 'sada'].forEach(function (id) { document.getElementById(id).addEventListener('input', calc); });
    document.getElementById('vrsta').addEventListener('change', calc);
    calc();
})();
</script>
@endpush
