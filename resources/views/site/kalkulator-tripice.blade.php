@extends('site.layout', [
    'title' => 'Kalkulator tripica — recept za bilo koju količinu',
    'description' => 'Klasične tripice s rajčicom i paprikom: upiši koliko tripica imaš i kalkulator preračuna sve sastojke. 500 g tripica je za 3 osobe.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kalkulatori">Kalkulatori</a> › Tripice</div>
    <h1 class="title">🍲 Kalkulator tripica</h1>
    <p class="lead">Osnova je recept za 500 g tripica, dovoljno za 3 osobe. Upiši svoju količinu i kalkulator preračuna povrće, slaninu, začine i vodu.</p>

    <div class="tool">
        <label for="g">Količina tripica</label>
        <input id="g" type="number" min="100" step="100" value="500" inputmode="decimal"> <span style="color:var(--muted);margin-left:6px;">g</span>
        <div class="hint">Dovoljno za otprilike <strong id="osobe"></strong>.</div>
        <table class="t" style="margin-top:22px;">
            <thead><tr><th>Sastojak</th><th class="num">Količina</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši popis</button>
    </div>

    <div class="prose">
        <h2>O ovom receptu</h2>
        <p>
            Ovo su klasične kontinentalne tripice u gustom crvenom umaku: osnova od luka i slanine, rajčica i paprika,
            a umak se zgušnjava zaprškom od brašna i krušnim mrvicama. „Hamburger" je, kako se kod nas zove,
            dimljena slanina — daje dimljeni okus koji tripicama pristaje.
        </p>
        <h3>Mjere u tablici</h3>
        <ul>
            <li><strong>žličica</strong> — mala, čajna žlica (oko 5 ml)</li>
            <li><strong>žlica</strong> — velika, jušna žlica (oko 15 ml)</li>
            <li><strong>češanj</strong> — jedan češanj češnjaka</li>
        </ul>
        <p>Više o mjerama i preračunavanju u vodiču <a href="/vodici/kuhinjske-mjere-zlica-salica-preracunavanje">Kuhinjske mjere</a>.</p>

        <h2>Ukratko, postupak</h2>
        <ol>
            <li>Tripice dobro operi i, ako nisu prethodno kuhane, kuhaj ih u vodi s mrkvom, celerom i peršinom dok ne omekšaju.</li>
            <li>Na slanini popirjaj sitno nasjeckan luk, dodaj češnjak, papriku i koncentrat rajčice.</li>
            <li>Dodaj narezane tripice, zalij temeljcem od kuhanja i kuhaj na laganoj vatri.</li>
            <li>Pred kraj zgusni zaprškom od brašna i mrvicama, posoli i dodaj tucanu papriku po ukusu.</li>
        </ol>
        <h2>Primjer: 2 kg tripica za društvo</h2>
        <p>
            Za 2 kg tripica faktor je 2000 ÷ 500 = 4, pa se sve iz osnovnog recepta množi s četiri. To je dovoljno za
            oko 12 osoba:
        </p>
        <table class="t">
            <tr><td>Hamburger i slanina</td><td class="num">po 400 g</td></tr>
            <tr><td>Crveni luk</td><td class="num">8 kom</td></tr>
            <tr><td>Češnjak</td><td class="num">8 češnjeva</td></tr>
            <tr><td>Mrkva</td><td class="num">2 kom</td></tr>
            <tr><td>Koncentrat rajčice i slatka paprika</td><td class="num">po 4 žličice</td></tr>
            <tr><td>Krušne mrvice</td><td class="num">8 žlica</td></tr>
            <tr><td>Voda</td><td class="num">6 l</td></tr>
        </table>
        <p>
            Kod velikih količina sol i ljutu papriku dodaj malo manje od izračunatog, pa probaj i dosoli na kraju.
            Vodu dodaj postupno — u širokom kotliću isparava drukčije nego u loncu.
        </p>

        <h2>Česta pitanja</h2>
        <h3>Koje tripice kupiti?</h3>
        <p>
            U trgovinama se najčešće prodaju <strong>očišćene i prethodno kuhane</strong> tripice, bijele boje. One su
            najjednostavnije: dovoljno ih je isprati, narezati na rezance i kuhati u umaku dok ne postanu mekane. Sirove,
            neočišćene tripice treba temeljito oprati i dugo kuhati prije nego što idu u umak.
        </p>
        <h3>Koliko se dugo kuhaju?</h3>
        <p>
            Ovisi o tripicama: prethodno kuhane u umaku obično omekšaju za sat do sat i pol, a sirove trebaju nekoliko sati.
            Najbolja provjera je okus — tripice trebaju biti mekane, ali ne raspadnute.
        </p>
        <h3>Kako se riješiti mirisa?</h3>
        <p>
            Tripice prije kuhanja dobro isperi u više voda, a neki ih prokuhaju nekoliko minuta i bace prvu vodu. Povrće
            (mrkva, celer, peršin), lovor i dimljena slanina u umaku ublaže preostali miris.
        </p>
        <h3>Mogu li se zamrznuti?</h3>
        <p>
            Gotove tripice u umaku dobro podnose zamrzavanje 2–3 mjeseca. Odmrzni ih u hladnjaku i podgrij do vrenja.
        </p>

        <p>Cijeli postupak, s čišćenjem tripica i savjetima kako izbjeći neugodan miris, u vodiču <a href="/vodici/tripice-tradicionalni-recept">Tripice na tradicionalni način</a>.</p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<script>
(function () {
    var BASE = 500;
    var S = [['Tripice', 500, 'g'], ['Mrkva', 0.5, 'kom'], ['Hamburger (dimljena slanina)', 100, 'g'], ['Slanina', 100, 'g'],
        ['Koncentrat rajčice', 1, 'žličica'], ['Slatka paprika', 1, 'žličica'], ['Crveni luk', 2, 'kom'], ['Češnjak', 2, 'češnja'],
        ['Krušne mrvice', 2, 'žlice'], ['Dodatak jelima', 2, 'žlice'], ['Brašno', 1, 'žlica'], ['Tucana ljuta paprika', 1, 'manja žličica'],
        ['Sol', 1, 'žličica'], ['Kocka za juhu', 1, 'kom'], ['Voda', 1500, 'ml'], ['Celer', 1, 'kom'], ['Peršin', 1, 'vezica']];
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 1 });
    function fmt(v, u) {
        if (u === 'g') return v >= 1000 ? nf.format(v / 1000) + ' kg' : Math.round(v) + ' g';
        if (u === 'ml') return v >= 1000 ? nf.format(v / 1000) + ' l' : Math.round(v) + ' ml';
        return nf.format(Math.round(v * 2) / 2 || 0.5) + ' ' + u;
    }
    function calc() {
        var g = Math.max(0, parseFloat(document.getElementById('g').value.replace(',', '.')) || 0), f = g / BASE;
        document.getElementById('rows').innerHTML = S.map(function (s) { return '<tr><td>' + s[0] + '</td><td class="num"><strong>' + fmt(s[1] * f, s[2]) + '</strong></td></tr>'; }).join('');
        var o = Math.max(1, Math.round(f * 3));
        document.getElementById('osobe').textContent = o + (o === 1 ? ' osobu' : o < 5 ? ' osobe' : ' osoba');
    }
    document.getElementById('g').addEventListener('input', calc);
    calc();
})();
</script>
@endpush
