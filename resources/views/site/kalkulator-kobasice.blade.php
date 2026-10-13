@extends('site.layout', [
    'title' => 'Kalkulator začina za kobasice po kg mesa',
    'description' => 'Koliko soli, paprike, papra, češnjaka i šećera ide u domaće kobasice? Upiši kilograme mesa i kalkulator izračuna grame. Npr. 10 kg mesa = 180 g soli.',
    'nav' => 'kalkulatori',
    'narrow' => true,
    'ads' => true,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/kalkulatori">Kalkulatori</a> › Kobasice</div>
    <h1 class="title">🌭 Kalkulator začina za kobasice</h1>
    <p class="lead">Upiši koliko kilograma mesa imaš, a kalkulator izračuna koliko grama svakog začina treba za klasične domaće kobasice za pečenje.</p>

    <div class="tool">
        <label for="kg">Količina mesa (s masnoćom)</label>
        <input id="kg" type="number" min="0.1" step="0.5" value="10" inputmode="decimal"> <span style="color:var(--muted);margin-left:6px;">kg</span>
        <div class="hint">Upisuje se ukupna masa samljevenog mesa i slanine, bez crijeva.</div>
        <table class="t" style="margin-top:22px;">
            <thead><tr><th>Začin</th><th class="num">% mase</th><th class="num">Količina</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
        <button type="button" class="btn ghost noprint" onclick="window.print()">Ispiši popis</button>
    </div>

    <div class="prose">
        <h2>Odakle ovi omjeri</h2>
        <p>
            Kalkulator koristi uobičajeni domaći omjer za kobasice za pečenje: <strong>1,8 % soli</strong> na masu mesa,
            po <strong>0,5 %</strong> slatke paprike, ljute paprike, češnjaka i šećera te <strong>0,3 %</strong> mljevenog papra.
            Za 10 kg mesa to je 180 g soli i ukupno oko 410 g začina.
        </p>
        <ul>
            <li><strong>Sol (1,8 %)</strong> — za svježe kobasice koje se peku ili kuhaju unutar par dana. Većini je ispod 1,5 % bljutavo, a iznad 2,2 % preslano.</li>
            <li><strong>Paprika</strong> — omjer slatke i ljute možeš mijenjati po ukusu; ukupno 1 % daje lijepu boju i okus.</li>
            <li><strong>Češnjak</strong> — računa se svježi, sitno nasjeckan ili protisnut. Ako koristiš češnjak u prahu, uzmi otprilike trećinu količine.</li>
            <li><strong>Šećer</strong> — ublažava sol i pomaže boji pri pečenju; nije nužan pa ga možeš smanjiti.</li>
        </ul>

        <div class="warn">
            <strong>Za sušene i dimljene kobasice</strong> (kulen, češnjovke koje se suše) ovi omjeri nisu dovoljni.
            Takve kobasice zahtijevaju nitritnu sol (salitru) u propisanoj količini i kontrolirano sušenje — bez toga
            postoji rizik od opasnih bakterija. Ovaj kalkulator je za svježe kobasice za pečenje.
        </div>

        <h2>Postupak u kratko</h2>
        <ol>
            <li>Meso i slaninu ohladi gotovo do smrzavanja i sameljaj na srednju rešetku (8–10 mm).</li>
            <li>Začine prvo izmiješaj zasebno, pa ih ravnomjerno pospi po mesu.</li>
            <li>Mijesi rukama nekoliko minuta dok smjesa ne postane ljepljiva — tada se dobro veže.</li>
            <li>Ispeci mali komadić i probaj sol <em>prije</em> punjenja.</li>
            <li>Napuni u isprana svinjska crijeva, ne prečvrsto, i izbockaj mjehuriće zraka.</li>
        </ol>
        <p>Cijeli postupak s izborom mesa i čuvanjem kobasica opisan je u vodiču
            <a href="/vodici/domace-kobasice-za-pecenje">Domaće kobasice za pečenje</a>.</p>
    </div>

    @include('site._ostali-kalkulatori')
@endsection

@push('scripts')
<script>
(function () {
    var Z = [['Sol', 1.8], ['Šećer', 0.5], ['Slatka paprika', 0.5], ['Ljuta paprika', 0.5], ['Mljeveni papar', 0.3], ['Bijeli luk (češnjak)', 0.5]];
    var nf = new Intl.NumberFormat('hr-HR', { maximumFractionDigits: 2 });
    function fmt(g) { return g >= 1000 ? nf.format(g / 1000) + ' kg' : Math.round(g) + ' g'; }
    function calc() {
        var kg = Math.max(0, parseFloat(document.getElementById('kg').value.replace(',', '.')) || 0), sum = 0, h = '';
        Z.forEach(function (z) { var g = kg * 1000 * z[1] / 100; sum += g; h += '<tr><td>' + z[0] + '</td><td class="num">' + nf.format(z[1]) + ' %</td><td class="num"><strong>' + fmt(g) + '</strong></td></tr>'; });
        h += '<tr class="total"><td>Ukupno začina</td><td class="num"></td><td class="num">' + fmt(sum) + '</td></tr>';
        document.getElementById('rows').innerHTML = h;
    }
    document.getElementById('kg').addEventListener('input', calc);
    calc();
})();
</script>
@endpush
