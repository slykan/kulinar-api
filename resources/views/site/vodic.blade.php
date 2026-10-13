@extends('site.layout', [
    'title' => $v['title'],
    'description' => $v['excerpt'],
    'nav' => 'vodici',
    'narrow' => true,
    'ads' => true,
    'ogType' => 'article',
    'jsonLd' => [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $v['title'],
        'description' => $v['excerpt'],
        'datePublished' => $v['date'],
        'inLanguage' => 'hr',
        'author' => ['@type' => 'Organization', 'name' => 'Kulinar.app', 'url' => url('/o-nama')],
        'publisher' => ['@type' => 'Organization', 'name' => 'Kulinar.app', 'logo' => ['@type' => 'ImageObject', 'url' => url('/icons/Icon-512.png')]],
    ],
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/vodici">Vodiči</a></div>
    <h1 class="title">{{ $v['title'] }}</h1>
    <div class="meta" style="margin-bottom:28px;">
        <span>Uredništvo Kulinar.app</span><span>· {{ \Illuminate\Support\Carbon::parse($v['date'])->format('d.m.Y.') }}</span><span>· {{ $v['read'] }} čitanja</span>
    </div>
    <div class="prose">
        @include('site.vodici.'.$v['slug'])
    </div>

    <div class="cta">
        <div><h3>Podijeli svoj recept</h3><p>Imaš svoju verziju? Objavi je u Kulinar.app aplikaciji i neka je zajednica ocijeni.</p></div>
        <a href="/app/" class="btn">Otvori aplikaciju</a>
    </div>

    <h2 class="sec">Pročitaj i ovo</h2>
    <div class="grid">
        @foreach ($ostali as $o)
            <a href="/vodici/{{ $o['slug'] }}" class="card"><div class="body"><h3>{{ $o['title'] }}</h3><p>{{ $o['excerpt'] }}</p></div></a>
        @endforeach
    </div>
@endsection
