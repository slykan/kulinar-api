@extends('site.layout', [
    'title' => $q !== '' ? 'Recepti: '.$q : 'Recepti zajednice',
    'description' => 'Domaći recepti koje su objavili korisnici Kulinar.app — sa sastojcima, postupkom i ocjenama.',
    'nav' => 'recepti',
    'index' => $q === '' && $posts->currentPage() === 1,
])

@section('content')
    <span class="eyebrow">Recepti</span>
    <h1 class="title">Recepti zajednice</h1>
    <p class="lead">Domaći recepti koje dijele korisnici Kulinar.app. Svoj recept možeš objaviti u <a href="/app/" style="color:var(--o);font-weight:700;">aplikaciji</a>.</p>

    <form method="get" action="/recepti" style="margin:26px 0 8px;display:flex;gap:10px;max-width:520px;">
        <input name="q" value="{{ $q }}" placeholder="Traži recept…" style="flex:1;padding:12px 14px;border-radius:11px;border:1.5px solid var(--line);background:var(--card);color:var(--text);font:inherit;">
        <button class="btn" type="submit">Traži</button>
    </form>

    @if ($posts->isEmpty())
        <p style="color:var(--muted);margin-top:24px;">Nema recepata za „{{ $q }}". <a href="/recepti" style="color:var(--o);">Prikaži sve</a></p>
    @else
        <div class="grid" style="margin-top:24px;">
            @foreach ($posts as $p) @include('site._recept-card', ['p' => $p]) @endforeach
        </div>
        @if ($posts->hasPages())
            <div style="display:flex;gap:12px;justify-content:center;margin-top:32px;">
                @if ($posts->previousPageUrl())<a class="btn ghost" href="{{ $posts->previousPageUrl() }}">← Novije</a>@endif
                @if ($posts->nextPageUrl())<a class="btn ghost" href="{{ $posts->nextPageUrl() }}">Starije →</a>@endif
            </div>
        @endif
    @endif
@endsection
