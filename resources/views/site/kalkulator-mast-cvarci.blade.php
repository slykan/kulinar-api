@extends('site.layout', [
    'title' => 'Kalkulator masti i čvaraka — koliko masti daje slanina',
    'description' => 'Upiši kilograme slanine i kalkulator procijeni koliko litara masti i kilograma čvaraka dobiješ, koliko staklenki pripremiti i koliko soli ide u čvarke. Npr. 10 kg slanine = oko 7 L masti.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kolinje">Kolinje</a> › Mast i čvarci</div>
    <h1 class="title">🔥 Kalkulator masti i čvaraka</h1>
    <p class="lead">Koliko masti i čvaraka daje slanina? Upiši kilograme i odaberi kakvu slaninu topiš, a kalkulator procijeni prinos, potrebne staklenke i sol za čvarke.</p>

    <div class="tool">
        <div class="red2">
            <div>
                <label for="kg">Slanina za topljenje</label>
                <input id="kg" type="number" min="0.5" step="0.5" value="10" inputmode="decimal"> <span style="color:var(--muted);margin-left:6px;">kg</span>
            </div>
            <div>
                <label for="tip">Kakva slanina</label>
                <select id="tip">
                    <option value="ledja">Leđna slanina bez kože (najviše masti)</option>
                    <option value="miks" selected>Slanina s malo mesa (klasični čvarci)</option>
                    <option value="salo">Salo / unutarnja mast (samo za mast)</option>
                </select>
            </div>
        </div>
        <div class="velike" id="velike"></div>
        <table class="t" style="margin-top:14px;">
            <thead><tr><th>Što treba</th><th class="num">Količina</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši</button>
    </div>

    <div class="prose">
        <h2>Odakle ovi omjeri</h2>
        <p>
            Kad se slanina topi, iz nje izađe voda i mast, a ostanu čvarci. Leđna slanina bez kože daje oko <strong>70 % masti</strong>
            i 12–15 % čvaraka. Slanina s malo mesa (klasični čvarci) daje manje masti, oko <strong>60 %</strong>, ali više i
            sočnijih čvaraka, 18–22 %. Salo daje najviše i najbjelje masti, a čvarci od njega su sitni.
        </p>
        <p>Mast je lakša od vode: 1 litra masti teži oko 0,9 kg. Zato kalkulator preračunava kilograme masti u litre i staklenke.</p>

        <h2>Kako topiti mast i čvarke</h2>
        <ol>
            <li>Slaninu nareži na kocke od 2–3 cm. Kožu ostavi ako voliš hrskave čvarke s kožicom, ali ih nareži manje.</li>
            <li>U kotao ili debeli lonac ulij malo vode (oko 1 dl na kg) da se slanina na početku ne zalijepi i ne zagori.</li>
            <li>Topi na srednjoj vatri i često miješaj drvenom kuhačom. Voda prvo ispari, a onda se kocke počnu pržiti u vlastitoj masti.</li>
            <li>Čvarci su gotovi kad postanu zlatni i potonu, a mast se izbistri. Za 10 kg to traje oko 2–3 sata.</li>
            <li>Mast procijedi kroz gazu u staklenke, a čvarke iscijedi u preši. Posoli ih dok su topli.</li>
        </ol>
        <div class="note">
            Mnogi pri kraju dodaju malo mlijeka (oko 1 dl na 5 kg). Čvarci tada dobiju ljepšu boju, ali pripazi jer mlijeko zapjeni
            i mast može zakipjeti. Detaljno u vodiču <a href="/vodici/domaca-mast-i-cvarci">Domaća mast i čvarci</a>.
        </div>

        <h2>Primjer: 10 kg slanine s malo mesa</h2>
        <table class="t">
            <tr><th>Što</th><th class="num">Okvirno</th></tr>
            <tr><td>Mast</td><td class="num">6 kg (oko 6,7 L)</td></tr>
            <tr><td>Čvarci</td><td class="num">2 kg</td></tr>
            <tr><td>Staklenke od 0,72 L</td><td class="num">10 kom</td></tr>
            <tr><td>Sol za čvarke</td><td class="num">30–40 g</td></tr>
        </table>

        <h2>Česta pitanja</h2>
        <h3>Koliko traje domaća mast?</h3>
        <p>
            Dobro procijeđena mast u zatvorenim staklenkama na hladnom (podrum, smočnica) traje 6–12 mjeseci, u hladnjaku i dulje.
            Kvari je voda, pa mast mora biti bistra, bez čestica i ostataka vode.
        </p>
        <h3>Zašto su mi čvarci tvrdi?</h3>
        <p>
            Najčešće zato što su se prepržili ili su kocke bile premale. Kad počnu tamniti, makni ih s vatre — prže se još neko vrijeme u
            vrućoj masti. Mekani čvarci se rade od slanine s više mesa i prže se kraće.
        </p>
        <h3>Koliko traju čvarci?</h3>
        <p>U hladnjaku 2–3 tjedna, a zamrznuti nekoliko mjeseci. Ako su zaliveni mašću u staklenci, traju i dulje.</p>
        <p>Recepti: <a href="/recepti/cvarci-domaci">domaći čvarci</a> i <a href="/recepti/pogacice-sa-cvarcima">pogačice sa čvarcima</a>.</p>
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
    var T = { ledja: [0.70, 0.14], miks: [0.60, 0.20], salo: [0.80, 0.08] };
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 1 });
    function calc() {
        var kg = Math.max(0, parseFloat(String(document.getElementById('kg').value).replace(',', '.')) || 0),
            t = T[document.getElementById('tip').value], mast = kg * t[0], cv = kg * t[1], l = mast / 0.9;
        document.getElementById('velike').innerHTML =
            '<div><b>' + nf.format(Math.round(mast * 2) / 2) + ' kg</b><span>masti (' + Math.round(t[0] * 100) + ' %)</span></div>' +
            '<div><b>' + nf.format(Math.round(l * 2) / 2) + ' L</b><span>masti u staklenkama</span></div>' +
            '<div><b>' + nf.format(Math.round(cv * 2) / 2) + ' kg</b><span>čvaraka (' + Math.round(t[1] * 100) + ' %)</span></div>';
        document.getElementById('rows').innerHTML =
            '<tr><td>Staklenke 0,72 L</td><td class="num"><strong>' + Math.ceil(l / 0.72) + ' kom</strong></td></tr>' +
            '<tr><td>ili staklenke 1 L</td><td class="num">' + Math.ceil(l) + ' kom</td></tr>' +
            '<tr><td>ili kante 5 L</td><td class="num">' + Math.ceil(l / 5) + ' kom</td></tr>' +
            '<tr><td>Voda na početku (1 dl/kg)</td><td class="num">' + nf.format(Math.round(kg) / 10) + ' L</td></tr>' +
            '<tr><td>Sol za čvarke (1,5–2 %)</td><td class="num">' + Math.round(cv * 15) + '–' + Math.round(cv * 20) + ' g</td></tr>' +
            '<tr><td>Mlijeko pri kraju (po želji)</td><td class="num">' + nf.format(Math.round(kg / 5 * 10) / 10) + ' dl</td></tr>' +
            '<tr><td>Vrijeme topljenja</td><td class="num">' + (kg <= 3 ? '1–1,5 h' : kg <= 12 ? '2–3 h' : '3–4 h') + '</td></tr>';
    }
    document.getElementById('kg').addEventListener('input', calc);
    document.getElementById('tip').addEventListener('change', calc);
    calc();
})();
</script>
@endpush
