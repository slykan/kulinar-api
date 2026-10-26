@php
    $img = \App\Http\Controllers\SiteController::slika($post->image);
    $ld = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Recipe',
        'name' => $post->title,
        'image' => $img ? [$img] : null,
        'author' => ['@type' => 'Person', 'name' => $post->user?->name ?? 'Kulinar.app'],
        'datePublished' => $post->published_at?->toDateString(),
        'description' => \App\Http\Controllers\SiteController::sazetak($post, 160),
        'recipeYield' => $post->servings ? \App\Http\Controllers\SiteController::osobe($post->servings) : null,
        'recipeIngredient' => $sastojci->all() ?: null,
        'recipeInstructions' => $koraci->map(fn ($k) => ['@type' => 'HowToStep', 'text' => $k])->all() ?: null,
        'inLanguage' => 'hr',
        'aggregateRating' => $post->rating_count > 0 ? ['@type' => 'AggregateRating', 'ratingValue' => $post->rating_average, 'ratingCount' => $post->rating_count] : null,
    ]);
@endphp
@extends('site.layout', [
    'title' => $post->title.' — recept',
    'description' => \App\Http\Controllers\SiteController::sazetak($post, 155),
    'nav' => 'recepti',
    'narrow' => true,
    'index' => $indeksiraj,
    'ads' => true,
    'ogType' => 'article',
    'ogImage' => $img,
    'jsonLd' => $ld,
])

@section('content')
    <div class="crumbs"><a href="/">Kulinar.app</a> › <a href="/recepti">Recepti</a> › {{ $post->title }}</div>
    <h1 class="title">{{ $post->title }}</h1>
    <div class="meta" style="font-size:.95rem;margin-bottom:22px;">
        @if ($post->user?->avatar)<img src="{{ $post->user->avatar }}" alt="" style="width:28px;height:28px;border-radius:50%;" referrerpolicy="no-referrer">@endif
        <span>{{ $post->user?->name }}</span>
        @if ($post->published_at)<span>· {{ $post->published_at->format('d.m.Y.') }}</span>@endif
        @if ($post->servings)<span>· {{ \App\Http\Controllers\SiteController::osobe($post->servings) }}</span>@endif
        @if ($post->rating_count > 0)<span>· <span class="stars">★ {{ number_format($post->rating_average, 1, ',', '') }}</span> ({{ $post->rating_count }} ocjena)</span>@endif
    </div>

    @if ($img)
        <img src="{{ $img }}" alt="{{ $post->title }}" style="width:100%;max-height:480px;object-fit:cover;border-radius:18px;margin-bottom:26px;">
    @endif

    @if ($post->excerpt)<p class="lead" style="margin-bottom:24px;">{{ $post->excerpt }}</p>@endif

    <div class="prose">
        @if ($sastojci->isNotEmpty())
            <h2>Sastojci @if ($post->servings)<span style="color:var(--dim);font-weight:600;font-size:1rem;">{{ \App\Http\Controllers\SiteController::osobe($post->servings, true) }}</span>@endif</h2>
            <ul>@foreach ($sastojci as $s)<li>{{ $s }}</li>@endforeach</ul>
        @endif

        @if ($koraci->isNotEmpty())
            <h2>Priprema</h2>
            <ol>@foreach ($koraci as $k)<li>{{ $k }}</li>@endforeach</ol>
        @endif
    </div>

    <div class="cta">
        <div><h3>Ocijeni ili spremi recept</h3><p>U aplikaciji možeš ocijeniti recept, spremiti ga u favorite i objaviti svoj.</p></div>
        <a href="/app/#/posts/{{ $post->slug }}" class="btn">Otvori u aplikaciji</a>
    </div>

    @if ($slicni->isNotEmpty())
        <h2 class="sec">Još recepata</h2>
        <div class="grid">@foreach ($slicni as $p) @include('site._recept-card', ['p' => $p]) @endforeach</div>
    @endif
@endsection
