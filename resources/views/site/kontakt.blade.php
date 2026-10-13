@extends('site.layout', ['title' => 'Kontakt', 'description' => 'Kontakt Kulinar.app — pitanja, prijedlozi i prijava neprimjerenog sadržaja.', 'narrow' => true])

@section('content')
    <span class="eyebrow">Kontakt</span>
    <h1 class="title">Javi nam se</h1>
    <div class="grid" style="margin-top:26px;">
        <a href="mailto:kontakt@kulinar.app" class="card"><div class="body"><div class="ico">✉️</div><h3>E-mail</h3><p>kontakt@kulinar.app</p></div></a>
        <div class="card"><div class="body"><div class="ico">📍</div><h3>Adresa</h3><p>On-Click<br>Ilirska 27, 31000 Osijek<br>Hrvatska</p></div></div>
    </div>
    <div class="prose" style="margin-top:32px;">
        <h2>Prijava sadržaja</h2>
        <p>Ako u nekom receptu primijetiš neprimjeren sadržaj ili sadržaj koji krši tuđa autorska prava, pošalji nam poveznicu na recept i kratko objašnjenje — provjerit ćemo ga i po potrebi ukloniti.</p>
        <h2>Brisanje računa</h2>
        <p>Korisnički račun i sve svoje recepte možeš obrisati sam u aplikaciji (Profil), ili nam pišite s adrese kojom ste se registrirali.</p>
    </div>
@endsection
