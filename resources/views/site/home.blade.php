@extends('site.layout', [
    'description' => 'Domaći recepti zajednice, besplatni kalkulatori za kobasice, tripice i turšiju te vodiči za domaću kuhinju — Kulinar.app.',
    'ads' => false, // list page — ads only next to recipes, guides and calculators
    'jsonLd' => [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Kulinar.app',
        'url' => url('/'),
        'inLanguage' => 'hr',
        'potentialAction' => ['@type' => 'SearchAction', 'target' => url('/recepti').'?q={q}', 'query-input' => 'required name=q'],
    ],
])

@section('content')
    <section style="display:grid;grid-template-columns:1.2fr .8fr;gap:40px;align-items:center;" class="hero">
        <div>
            <span class="eyebrow">Domaća kuhinja, bez pogađanja</span>
            <h1 class="title">Recepti, kalkulatori i vodiči za domaću kuhinju</h1>
            <p class="lead">Koliko soli ide u 10 kg kobasica? Koliko octa za turšiju? Kulinar.app preračunava domaće recepte umjesto tebe — a zajednica dijeli svoje provjerene recepte.</p>
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:26px;">
                <a href="/kalkulatori" class="btn">Kalkulatori →</a>
                <a href="/recepti" class="btn ghost">Recepti zajednice ({{ $brojRecepata }})</a>
            </div>
        </div>
        <div class="card" style="padding:24px;">
            <span class="pill" style="align-self:flex-start;">Primjer: 10 kg kobasica</span>
            <table class="t" style="margin-top:12px;">
                <tr><td>Sol</td><td class="num">180 g</td></tr>
                <tr><td>Slatka paprika</td><td class="num">50 g</td></tr>
                <tr><td>Ljuta paprika</td><td class="num">50 g</td></tr>
                <tr><td>Bijeli luk</td><td class="num">50 g</td></tr>
                <tr><td>Šećer</td><td class="num">50 g</td></tr>
                <tr><td>Papar</td><td class="num">30 g</td></tr>
            </table>
            <a href="/kalkulatori/kobasice" style="color:var(--o);font-weight:700;">Izračunaj za svoju količinu →</a>
        </div>
    </section>

    <h2 class="sec">Kalkulatori <a href="/kalkulatori">Svi kalkulatori →</a></h2>
    <div class="grid">
        @foreach ($kalkulatori as $slug => $k)
            <a href="/kalkulatori/{{ $slug }}" class="card"><div class="body">
                <div class="ico">{{ $k['ico'] }}</div><h3>{{ $k['naziv'] }}</h3><p>{{ $k['opis'] }}</p>
            </div></a>
        @endforeach
    </div>

    @if ($recepti->isNotEmpty())
        <h2 class="sec">Najnoviji recepti zajednice <a href="/recepti">Svi recepti →</a></h2>
        <div class="grid">
            @foreach ($recepti as $p) @include('site._recept-card', ['p' => $p]) @endforeach
        </div>
    @endif

    <h2 class="sec">Vodiči <a href="/vodici">Svi vodiči →</a></h2>
    <div class="grid">
        @foreach ($vodici as $v)
            <a href="/vodici/{{ $v['slug'] }}" class="card"><div class="body">
                <span class="pill" style="align-self:flex-start;">Vodič · {{ $v['read'] }}</span>
                <h3>{{ $v['title'] }}</h3><p>{{ $v['excerpt'] }}</p>
            </div></a>
        @endforeach
    </div>

    <div class="cta">
        <div><h3>Imaš recept koji svi traže?</h3><p>Objavi ga u aplikaciji — sa sastojcima, fotografijom i ocjenama zajednice.</p></div>
        <a href="/app/" class="btn">Otvori aplikaciju</a>
    </div>

    <style>@media (max-width: 760px) { .hero { grid-template-columns: 1fr !important; } }</style>
@endsection
