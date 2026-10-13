@extends('site.layout', ['title' => 'Stranica nije pronađena', 'index' => false, 'narrow' => true])

@section('content')
    <span class="eyebrow">404</span>
    <h1 class="title">Ova stranica ne postoji</h1>
    <p class="lead">Možda je recept uklonjen ili je adresa pogrešno upisana.</p>
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:26px;">
        <a href="/recepti" class="btn">Recepti</a>
        <a href="/kalkulatori" class="btn ghost">Kalkulatori</a>
        <a href="/" class="btn ghost">Naslovnica</a>
    </div>
@endsection
