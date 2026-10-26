@extends('site.layout', [
    'title' => 'Kalkulator salamure za turšiju — ocat, voda, sol, šećer',
    'description' => 'Koliko vode, soli i šećera ide na litru octa za turšiju? Omjer 1:3 — upiši litre octa i dobiješ cijelu salamuru.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kalkulatori">Kalkulatori</a> › Turšija</div>
    <h1 class="title">🥒 Kalkulator salamure za turšiju</h1>
    <p class="lead">Klasična salamura za miješanu turšiju: ocat i voda u omjeru 1:3, uz sol, šećer, papar i lovor. Upiši litre octa — ostalo kalkulator preračuna.</p>

    <div class="tool">
        <label for="l">Količina octa</label>
        <input id="l" type="number" min="0.25" step="0.25" value="1" inputmode="decimal"> <span style="color:var(--muted);margin-left:6px;">l</span>
        <div class="hint">Računa se s običnim alkoholnim octom od 9 %. Ukupno salamure: <strong id="ukupno"></strong>.</div>
        <table class="t" style="margin-top:22px;">
            <thead><tr><th>Sastojak</th><th class="num">Količina</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši popis</button>
    </div>

    <div class="prose">
        <h2>Omjer 1:3 — što to znači</h2>
        <p>
            Na <strong>1 litru octa</strong> ide <strong>3 litre vode</strong>, <strong>150 g soli</strong> i <strong>250 g šećera</strong>.
            Ta salamura je kiselo-slatka i blaga, kakvu većina pamti iz bakine špajze. Ako voliš kiseliju turšiju,
            smanji vodu na omjer 1:2; ako voliš slađu, šećer se može povećati.
        </p>
        <h3>Koliko salamure treba?</h3>
        <p>
            Kao gruba procjena, na svaki kilogram narezanog povrća treba oko pola litre salamure — ali najsigurnije je
            povrće složiti u tegle i izmjeriti koliko vode stane. Bolje je skuhati malo više nego dolijevati.
        </p>
        <h2>Postupak</h2>
        <ol>
            <li>Ocat, vodu, sol i šećer zakuhaj dok se sol i šećer ne otope. Dodaj papar u zrnu i lovor.</li>
            <li>Povrće (paprika, krastavci, mrkva, cvjetača, luk…) operi i nareži, pa ga čvrsto složi u čiste tegle.</li>
            <li>Prelij vrućom salamurom tako da povrće bude potpuno prekriveno, i zatvori tegle.</li>
            <li>Tegle pasteriziraj u vodenoj kupelji ili ih ostavi da se polako hlade umotane u deku.</li>
        </ol>
        <h2>Primjer: 5 kg povrća</h2>
        <p>
            Za oko 5 kg narezanog povrća treba otprilike 2,5 l salamure. Prema omjeru 1:3 to je:
        </p>
        <table class="t">
            <tr><td>Alkoholni ocat (9 %)</td><td class="num">0,625 l</td></tr>
            <tr><td>Voda</td><td class="num">1,875 l</td></tr>
            <tr><td>Sol</td><td class="num">94 g</td></tr>
            <tr><td>Šećer</td><td class="num">156 g</td></tr>
            <tr class="total"><td>Ukupno salamure</td><td class="num">oko 2,5 l</td></tr>
        </table>
        <p>U praksi zaokruži na 0,7 l octa i 2 l vode — malo jača salamura nikad ne škodi.</p>

        <h2>Česta pitanja</h2>
        <h3>Koji ocat?</h3>
        <p>
            Omjeri u kalkulatoru računaju se za <strong>alkoholni ocat od 9 %</strong>. Ako koristiš ocat od 5 % (jabučni,
            vinski), treba ga gotovo dvostruko više za istu kiselost — u omjeru otprilike 1:1 s vodom umjesto 1:3. Kod
            slabijeg octa nemoj smanjivati količinu, jer kiselina je ono što turšiju čuva.
        </p>
        <h3>Može li turšija bez šećera?</h3>
        <p>
            Može — šećer je za okus, a turšiju konzerviraju ocat i sol. Bez šećera turšija je kiselija i oštrija.
        </p>
        <h3>Treba li konzervans?</h3>
        <p>
            Ne mora. Uz dovoljno octa, čiste tegle i pasterizaciju turšija traje do proljeća. Konzervansi (npr. natrijev
            benzoat) se mogu dodati prema uputi na pakiranju, ali ne zamjenjuju čistoću i pasterizaciju.
        </p>
        <h3>Zašto se salamura zamutila?</h3>
        <p>
            Ako je mutnoća prisutna od prvog dana, uzrok je često sol s dodacima ili tvrda voda. Ako se pojavi kasnije
            uz pjenu, napuhnut poklopac ili neugodan miris, turšija se pokvarila i treba je baciti. Kako pravilno zatvoriti
            tegle, objašnjava vodič <a href="/vodici/zimnica-sterilizacija-pasterizacija-staklenki">zimnica: sterilizacija i pasterizacija</a>.
        </p>

        <p>Detaljno o izboru povrća, pasterizaciji i čuvanju u vodiču <a href="/vodici/tursija-salamura-omjeri">Turšija: omjer octa i vode, salamura i pasterizacija</a>.</p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<script>
(function () {
    var S = [['Ocat (9 %)', 1, 'l'], ['Voda', 3, 'l'], ['Sol', 150, 'g'], ['Šećer', 250, 'g'], ['Papar u zrnu', 2, 'žlice'], ['Lovor', 3, 'lista']];
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 2 });
    function fmt(v, u) {
        if (u === 'g') return v >= 1000 ? nf.format(v / 1000) + ' kg' : Math.round(v) + ' g';
        if (u === 'l') return v < 1 ? Math.round(v * 1000) + ' ml' : nf.format(v) + ' l';
        return nf.format(Math.round(v * 2) / 2) + ' ' + u;
    }
    function calc() {
        var l = Math.max(0, parseFloat(document.getElementById('l').value.replace(',', '.')) || 0);
        document.getElementById('rows').innerHTML = S.map(function (s) { return '<tr><td>' + s[0] + '</td><td class="num"><strong>' + fmt(s[1] * l, s[2]) + '</strong></td></tr>'; }).join('');
        document.getElementById('ukupno').textContent = fmt(4 * l, 'l');
    }
    document.getElementById('l').addEventListener('input', calc);
    calc();
})();
</script>
@endpush
