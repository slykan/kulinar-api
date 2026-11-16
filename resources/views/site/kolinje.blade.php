@extends('site.layout', [
    'title' => 'Kolinje — kalkulatori, vodiči i recepti na jednom mjestu',
    'description' => 'Sve za kolinje: koliko mesa daje svinja, začini za kobasice i kulen, soljenje šunke i slanine, mast i čvarci, crijeva, dimljenje i sušenje te recepti za krvavice, švarglu i čvarke.',
    'nav' => 'kolinje',
    'ads' => true,
    'jsonLd' => [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Kolinje — kalkulatori, vodiči i recepti',
        'url' => url('/kolinje'),
        'inLanguage' => 'hr',
    ],
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › Kolinje</div>
    <section class="kol-hero">
        <div>
            <span class="eyebrow">Sezona kolinja</span>
            <h1 class="title">Kolinje: od svinje do pune pušnice</h1>
            <p class="lead">Kalkulatori koji preračunaju sol, začine, crijeva i staklenke za tvoju količinu, vodiči za svaki korak i provjereni recepti za krvavice, švarglu i čvarke.</p>
        </div>
        <figure class="kol-slika slika-izvor">
            <img src="/storage/kolinje/hero.jpg" alt="Domaći kulen narezan na dasci" width="840" height="630">
            <figcaption>{!! \App\Http\Controllers\SiteController::izvorSlike('kolinje/hero.jpg') !!}</figcaption>
        </figure>
    </section>

    <h2 class="sec">Redoslijed poslova</h2>
    <ol class="kol-tijek">
        <li><b>Tjedan prije</b><span>Izračunaj <a href="/kalkulatori/svinja">koliko mesa daje svinja</a>, nabavi <a href="/kalkulatori/crijeva">crijeva</a>, sol, nitritnu sol, papriku i staklenke. Dogovori veterinara.</span></li>
        <li><b>Dan kolinja</b><span>Klanje, krv, iznutrice, rasijecanje. <a href="/vodici/kolinje-krvavice-svargla-pecenice">Krvavice i švargla</a>, <a href="/kalkulatori/mast-cvarci">mast i čvarci</a>.</span></li>
        <li><b>Drugi dan</b><span>Ohlađeno meso ide u <a href="/kalkulatori/kobasice">kobasice</a> i <a href="/kalkulatori/kulen">kulen</a>, a butovi, panceta i slanina u <a href="/kalkulatori/soljenje">sol</a>.</span></li>
        <li><b>Tjedni poslije</b><span><a href="/vodici/dimljenje-mesa-drvo-trajanje">Dimljenje</a>, pa <a href="/vodici/susenje-i-cuvanje-suhog-mesa">sušenje</a> uz vagu i <a href="/kalkulatori/susenje">kalkulator sušenja</a>.</span></li>
    </ol>

    <h2 class="sec">Kalkulatori za kolinje</h2>
    <div class="grid">
        @foreach ($kalkulatori as $slug => $k)
            <a href="/kalkulatori/{{ $slug }}" class="card"><div class="body">
                <div class="ico">{{ $k['ico'] }}</div><h3>{{ $k['naziv'] }}</h3><p>{{ $k['opis'] }}</p>
            </div></a>
        @endforeach
    </div>

    <h2 class="sec">Vodiči</h2>
    <div class="grid">
        @foreach ($vodici as $v)
            <a href="/vodici/{{ $v['slug'] }}" class="card"><div class="body">
                <span class="pill" style="align-self:flex-start;">Vodič · {{ $v['read'] }}</span>
                <h3>{{ $v['title'] }}</h3><p>{{ $v['excerpt'] }}</p>
            </div></a>
        @endforeach
    </div>

    @if ($recepti->isNotEmpty())
        <h2 class="sec">Recepti s kolinja</h2>
        <div class="grid">
            @foreach ($recepti as $p) @include('site._recept-card', ['p' => $p]) @endforeach
        </div>
    @endif

    <div class="prose" style="max-width:780px;margin-top:40px;">
        <h2>Tri pravila za sigurno kolinje</h2>
        <ul>
            <li><strong>Hladno.</strong> Meso se obrađuje ohlađeno na 0–4 °C, a sol i suši na hladnom. Toplo vrijeme je najveći neprijatelj kolinja.</li>
            <li><strong>Vaga, ne „od oka”.</strong> Sol, nitritna sol i začini važu se prema masi mesa — zato postoje kalkulatori.</li>
            <li><strong>Veterinar.</strong> Meso iz domaćeg klanja mora proći pregled na trihinelu prije nego što se jede ili prerađuje u kulen, suhe kobasice i pršut.</li>
        </ul>
    </div>
@endsection

@push('scripts')
<style>
    .kol-hero { display: grid; grid-template-columns: 1.1fr .9fr; gap: 36px; align-items: center; }
    .kol-slika { margin: 0; }
    .kol-tijek { list-style: none; display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; counter-reset: k; }
    .kol-tijek li { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 18px; counter-increment: k; position: relative; }
    .kol-tijek li::before { content: counter(k); position: absolute; top: 14px; right: 16px; font-weight: 900; font-size: 1.6rem; color: var(--o); opacity: .5; }
    .kol-tijek b { display: block; font-size: 1.05rem; margin-bottom: 6px; color: #fff; }
    .kol-tijek span { color: var(--muted); font-size: .93rem; }
    .kol-tijek a { color: var(--o); font-weight: 700; }
    @media (max-width: 900px) { .kol-hero, .kol-tijek { grid-template-columns: 1fr; } }
</style>
@endpush
