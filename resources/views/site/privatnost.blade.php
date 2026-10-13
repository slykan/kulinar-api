@extends('site.layout', ['title' => 'Politika privatnosti', 'description' => 'Kako Kulinar.app obrađuje osobne podatke, kolačiće i oglase.', 'narrow' => true])

@section('content')
    <span class="eyebrow">Pravno</span>
    <h1 class="title">Politika privatnosti</h1>
    <p class="lead">Vrijedi od 13. listopada 2026.</p>
    <div class="prose" style="margin-top:24px;">
        <h2>1. Tko obrađuje podatke</h2>
        <p>Voditelj obrade je <strong>On-Click</strong>, Ilirska 27, 31000 Osijek, Hrvatska. Kontakt za pitanja o privatnosti: <a href="mailto:kontakt@kulinar.app">kontakt@kulinar.app</a>.</p>

        <h2>2. Koje podatke prikupljamo</h2>
        <ul>
            <li><strong>Korisnički račun</strong> — ime, e-mail, lozinka (pohranjena samo kao kriptografski sažetak) ili podaci iz Google prijave (ime, e-mail, profilna slika).</li>
            <li><strong>Sadržaj koji objaviš</strong> — recepti, fotografije, ocjene i spremljeni recepti. Objavljeni recepti su javni i prikazuju se s tvojim imenom.</li>
            <li><strong>Tehnički podaci</strong> — IP adresa i zapisi o pristupu, radi sigurnosti i rada usluge.</li>
        </ul>

        <h2>3. Zašto</h2>
        <ul>
            <li>za rad usluge (prijava, objava i prikaz recepata, ocjene, favoriti),</li>
            <li>za sigurnost i sprječavanje zlouporabe (npr. Cloudflare Turnstile pri registraciji),</li>
            <li>za prikaz oglasa na javnim stranicama s receptima, kalkulatorima i vodičima.</li>
        </ul>

        <h2>4. Kolačići i oglasi</h2>
        <p>
            Na javnim stranicama prikazujemo oglase putem usluge <strong>Google AdSense</strong>. Google i njegovi partneri
            mogu koristiti kolačiće za prikaz oglasa na temelju tvojih prethodnih posjeta ovoj i drugim stranicama.
            Personalizirane oglase možeš isključiti u <a href="https://adssettings.google.com" target="_blank" rel="noopener">Googleovim postavkama oglasa</a>,
            a više o tome pročitaj na <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener">policies.google.com</a>.
            Kalkulatori rade u tvom pregledniku — upisane količine ne šalju se nikamo.
        </p>

        <h2>5. Kome dajemo podatke</h2>
        <p>Podatke ne prodajemo. Koristimo pružatelje usluga koji ih obrađuju po našem nalogu: hosting poslužitelja, Cloudflare (zaštita i dostava sadržaja) i Google (prijava putem Google računa, oglasi).</p>

        <h2>6. Koliko dugo</h2>
        <p>Podatke čuvamo dok je račun aktivan. Brisanjem računa u aplikaciji brišu se račun i tvoji recepti.</p>

        <h2>7. Tvoja prava</h2>
        <p>Imaš pravo na pristup, ispravak, brisanje i prenosivost podataka, prigovor na obradu te povlačenje privole. Zahtjev pošalji na kontakt@kulinar.app. Pritužbu možeš podnijeti Agenciji za zaštitu osobnih podataka (azop.hr).</p>
    </div>
@endsection
