@extends('site.layout', [
    'title' => 'Kalkulator soljenja šunke, pancete i slanine',
    'description' => 'Koliko soli i nitritne soli po kg mesa za šunku, pancetu, slaninu i vratinu, suho soljenje ili salamura, i koliko dana meso stoji u soli. Npr. šunka od 10 kg: 300 g soli, oko 15 dana.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kolinje">Kolinje</a> › Soljenje mesa</div>
    <h1 class="title">🥓 Kalkulator soljenja šunke, pancete i slanine</h1>
    <p class="lead">Odaberi komad mesa i način soljenja, upiši težinu, a kalkulator izračuna sol, nitritnu sol, začine i okvirno koliko dana meso treba stajati u soli prije dimljenja i sušenja.</p>

    <div class="tool">
        <div class="red3">
            <div>
                <label for="komad">Komad mesa</label>
                <select id="komad">
                    <option value="sunka">But / šunka (s kosti)</option>
                    <option value="panceta">Panceta (potrbušina)</option>
                    <option value="slanina">Leđna slanina</option>
                    <option value="vrat">Vratina / pečenica</option>
                    <option value="rebra">Rebra</option>
                </select>
            </div>
            <div>
                <label for="nacin">Način</label>
                <select id="nacin">
                    <option value="suho">Suho soljenje</option>
                    <option value="salamura">Salamura (rasol)</option>
                </select>
            </div>
            <div>
                <label for="kg">Težina mesa</label>
                <input id="kg" type="number" min="0.5" step="0.5" value="10" inputmode="decimal"> <span style="color:var(--muted);margin-left:4px;">kg</span>
            </div>
            <div id="debljinaBox">
                <label for="cm">Debljina komada</label>
                <input id="cm" type="number" min="1" step="0.5" value="5" inputmode="decimal"> <span style="color:var(--muted);margin-left:4px;">cm</span>
            </div>
        </div>
        <div class="hint" id="hint"></div>

        <table class="t" style="margin-top:22px;">
            <thead><tr><th>Sastojak</th><th class="num">Omjer</th><th class="num">Količina</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <div class="note" id="dani" style="margin:0 0 16px;"></div>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši</button>
    </div>

    <div class="prose">
        <h2>Odakle ovi omjeri</h2>
        <p>
            Kalkulator računa <strong>suho soljenje po težini</strong>: sva sol se izvaže prema masi mesa i utrlja u meso, pa meso
            ne može biti preslano kao kad se „zakopa u sol”. Za šunku je to 3 %, za pancetu i vratinu 2,8 %, za slaninu 3,5 %.
            U <strong>salamuri</strong> meso stoji u rasolu od 90 g soli na litru vode.
        </p>
        <ul>
            <li><strong>Nitritna sol (salitrena sol)</strong> — kupuje se gotova, s oko 0,5–0,6 % natrijevog nitrita. Kalkulator uzima pola soli kao nitritnu, a pola kao običnu. Nitrit čuva crvenu boju i štiti meso od opasnih bakterija tijekom sušenja. Nikad ne koristi čisti nitrit ili salitru „od oka”.</li>
            <li><strong>Šećer</strong> — malo ublažava sol i pomaže boji. Može se izostaviti.</li>
            <li><strong>Papar, češnjak, lovor</strong> — po ukusu. Kod pancete se često doda i ružmarin.</li>
        </ul>

        <h2>Koliko dana u soli</h2>
        <p>
            Sol do sredine komada putuje polako. Za šunku se računa <strong>oko 1,5 dan po kilogramu</strong>, a za ravne komade
            (panceta, slanina, vrat) <strong>oko 2 dana po centimetru debljine</strong>. Kraće soljenje ostavlja sirovu sredinu koja
            se može pokvariti uz kost, a duže samo daje slanije meso.
        </p>
        <ol>
            <li>Meso ohladi na 2–4 °C (ne smrzni) i obreži neravne rubove.</li>
            <li>Izvaži sol, nitritnu sol i začine, pomiješaj i dobro utrljaj u meso, posebno uz kost i u pregibe.</li>
            <li>Slaži u posudu ili vreću, kožom prema dolje. Drži na 2–6 °C.</li>
            <li>Svaka 2–3 dana preokreni komade i premaži ih tekućinom koja se skupila.</li>
            <li>Nakon soljenja meso operi mlakom vodom, ocijedi i ostavi da se prosuši 1–2 dana prije dimljenja.</li>
        </ol>

        <div class="warn">
            Soljenje se radi na hladnom: iznad 8 °C meso se može pokvariti prije nego što ga sol zaštiti. Ako je vani toplo, soli u
            hladnjaku ili hladnoj komori. Šunka i pršut se nakon dimljenja suše mjesecima — vidi
            <a href="/vodici/susenje-i-cuvanje-suhog-mesa">sušenje i čuvanje suhog mesa</a>.
        </div>

        <h2>Primjer: šunka od 10 kg, suho soljenje</h2>
        <table class="t">
            <tr><th>Sastojak</th><th class="num">Količina</th></tr>
            <tr><td>Morska sol</td><td class="num">150 g</td></tr>
            <tr><td>Nitritna sol</td><td class="num">150 g</td></tr>
            <tr><td>Šećer</td><td class="num">30 g</td></tr>
            <tr><td>Mljeveni papar</td><td class="num">20 g</td></tr>
            <tr><td>Češnjak</td><td class="num">30 g</td></tr>
            <tr><td>Soljenje</td><td class="num">oko 15 dana</td></tr>
        </table>

        <h2>Česta pitanja</h2>
        <h3>Suho soljenje ili salamura?</h3>
        <p>
            Suho soljenje daje čvršće, aromatičnije meso i manje se gubi na težini kasnije pri sušenju. Salamura je zgodna za veće
            količine i rebra jer se sol ravnomjernije raspodijeli, a meso ostane sočnije. Za pršut se koristi samo suho soljenje.
        </p>
        <h3>Mogu li soliti samo običnom soli?</h3>
        <p>
            Za meso koje se peče ili kuha u roku od par tjedana — da. Za šunku, pancetu i slaninu koje se suše i dime preporučuje se
            nitritna sol, osim ako imaš iskustva i stalno hladne uvjete (ispod 6 °C) cijelo vrijeme soljenja.
        </p>
        <h3>Kako znam da je meso dovoljno usoljeno?</h3>
        <p>
            Meso postane čvršće i tamnije, a kod šunke se uz kost osjeti tvrdoća. Kod suhog soljenja izgubi 3–5 % težine.
        </p>
        <p>Dalje: <a href="/vodici/dimljenje-mesa-drvo-trajanje">dimljenje mesa</a> i <a href="/kalkulatori/susenje">kalkulator sušenja</a>.</p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<style>
    .red3 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 18px; }
    .red3 select, .red3 input { max-width: 100%; }
    .red3 input { max-width: 160px; }
    @media (max-width: 760px) { .red3 { grid-template-columns: 1fr; } }
</style>
<script>
(function () {
    // sol (%) za suho soljenje; dani: šunka po kg, ostalo po cm debljine
    var K = {
        sunka:   { sol: 3.0, ime: 'šunka',   poKg: 1.5, min: 10, max: 35 },
        panceta: { sol: 2.8, ime: 'panceta', poCm: 2, min: 7, max: 21 },
        slanina: { sol: 3.5, ime: 'slanina', poCm: 2, min: 7, max: 21 },
        vrat:    { sol: 2.8, ime: 'vratina', poCm: 2, min: 7, max: 18 },
        rebra:   { sol: 2.8, ime: 'rebra',   poCm: 2, min: 5, max: 10 }
    };
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 2 });
    function g(x) { return x >= 1000 ? nf.format(Math.round(x / 10) / 100) + ' kg' : Math.round(x) + ' g'; }
    function broj(id) { return Math.max(0, parseFloat(String(document.getElementById(id).value).replace(',', '.')) || 0); }
    function calc() {
        var k = K[document.getElementById('komad').value], salamura = document.getElementById('nacin').value === 'salamura',
            kg = broj('kg'), cm = broj('cm'), h = '', dani;
        document.getElementById('debljinaBox').style.display = k.poKg ? 'none' : '';
        dani = k.poKg ? kg * k.poKg : cm * k.poCm;
        dani = Math.round(Math.min(k.max, Math.max(k.min, dani)));
        if (!salamura) {
            var sol = kg * 10 * k.sol;
            h += '<tr><td>Morska sol</td><td class="num">' + nf.format(k.sol / 2) + ' %</td><td class="num"><strong>' + g(sol / 2) + '</strong></td></tr>';
            h += '<tr><td>Nitritna sol</td><td class="num">' + nf.format(k.sol / 2) + ' %</td><td class="num"><strong>' + g(sol / 2) + '</strong></td></tr>';
            h += '<tr><td>Šećer</td><td class="num">0,3 %</td><td class="num">' + g(kg * 3) + '</td></tr>';
            h += '<tr><td>Mljeveni papar</td><td class="num">0,2 %</td><td class="num">' + g(kg * 2) + '</td></tr>';
            h += '<tr><td>Češnjak (svježi)</td><td class="num">0,3 %</td><td class="num">' + g(kg * 3) + '</td></tr>';
            h += '<tr><td>Lovor</td><td class="num"></td><td class="num">' + Math.max(2, Math.round(kg / 2)) + ' listova</td></tr>';
            h += '<tr class="total"><td>Ukupno soli</td><td class="num">' + nf.format(k.sol) + ' %</td><td class="num">' + g(sol) + '</td></tr>';
            document.getElementById('hint').textContent = 'Suho soljenje: sva sol se utrlja u meso. Omjer za ' + k.ime + ' je ' + nf.format(k.sol) + ' % soli na masu mesa.';
        } else {
            var l = Math.max(1, Math.ceil(kg * 0.6 * 2) / 2);
            dani = Math.round(dani * 0.8);
            h += '<tr><td>Voda (prokuhana, ohlađena)</td><td class="num">60 % mase</td><td class="num"><strong>' + nf.format(l) + ' L</strong></td></tr>';
            h += '<tr><td>Morska sol</td><td class="num">45 g/L</td><td class="num"><strong>' + g(l * 45) + '</strong></td></tr>';
            h += '<tr><td>Nitritna sol</td><td class="num">45 g/L</td><td class="num"><strong>' + g(l * 45) + '</strong></td></tr>';
            h += '<tr><td>Šećer</td><td class="num">10 g/L</td><td class="num">' + g(l * 10) + '</td></tr>';
            h += '<tr><td>Papar u zrnu</td><td class="num">3 g/L</td><td class="num">' + g(l * 3) + '</td></tr>';
            h += '<tr><td>Češnjak</td><td class="num">1 glavica / 5 L</td><td class="num">' + Math.max(1, Math.round(l / 5)) + ' glav.</td></tr>';
            h += '<tr><td>Lovor</td><td class="num"></td><td class="num">' + Math.max(3, Math.round(l)) + ' listova</td></tr>';
            document.getElementById('hint').textContent = 'Salamura: 90 g soli na litru vode (pola nitritna). Meso mora biti potpuno pod rasolom — ako ga treba više, povećaj sve razmjerno.';
        }
        document.getElementById('rows').innerHTML = h;
        document.getElementById('dani').innerHTML = '<strong>Soljenje: oko ' + dani + ' dana</strong> na 2–6 °C' +
            (k.poKg ? ' (1,5 dan po kg).' : ' (oko 2 dana po cm debljine).') + ' Komade preokreni svaka 2–3 dana.';
    }
    ['komad', 'nacin'].forEach(function (id) { document.getElementById(id).addEventListener('change', calc); });
    ['kg', 'cm'].forEach(function (id) { document.getElementById(id).addEventListener('input', calc); });
    calc();
})();
</script>
@endpush
