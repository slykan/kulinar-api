@extends('site.layout', ['title' => 'O nama', 'description' => 'Kulinar.app — recepti zajednice, kalkulatori i vodiči za domaću kuhinju. Tko stoji iza projekta.', 'narrow' => true])

@section('content')
    <span class="eyebrow">O nama</span>
    <h1 class="title">Domaća kuhinja, preračunata</h1>
    <div class="prose">
        <p>
            Kulinar.app je nastao iz jednog vrlo konkretnog problema: svake jeseni isto pitanje — koliko soli ide u
            kobasice, koliko octa za turšiju, kako recept „za cijelu svinju" pretvoriti u recept za 8 kila mesa.
            Recepti iz bilježnica i s papirića rijetko imaju omjere, pa smo napravili kalkulatore koji ih imaju.
        </p>
        <h2>Što nudimo</h2>
        <ul>
            <li><strong><a href="/kalkulatori">Kalkulatori</a></strong> — začini za kobasice po kilogramu mesa, salamura za turšiju, tripice za bilo koju količinu.</li>
            <li><strong><a href="/recepti">Recepti zajednice</a></strong> — recepte objavljuju korisnici, a ostali ih mogu ocijeniti i spremiti.</li>
            <li><strong><a href="/vodici">Vodiči</a></strong> — postupci korak po korak, s omjerima i savjetima.</li>
            <li><strong><a href="/app/">Aplikacija</a></strong> — za web, Android i iOS, s prijavom, favoritima i objavom vlastitih recepata.</li>
        </ul>
        <h2>Tko stoji iza projekta</h2>
        <p>
            Kulinar.app razvija <strong>On-Click</strong> iz Osijeka. Recepte objavljuju korisnici i oni su autori svojih
            recepata; vodiče i kalkulatore priprema uredništvo Kulinar.app.
        </p>
        <h2>Kontakt</h2>
        <p>Prijedlozi, ispravci i pitanja: <a href="mailto:kontakt@kulinar.app">kontakt@kulinar.app</a> · više na stranici <a href="/kontakt">Kontakt</a>.</p>
    </div>
@endsection
