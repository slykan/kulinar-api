@extends('site.layout', [
    'title' => 'Kalkulatori za domaću kuhinju',
    'description' => 'Besplatni kalkulatori: začini za domaće kobasice po kilogramu mesa, salamura za turšiju i tripice za bilo koju količinu.',
    'nav' => 'kalkulatori',
    'ads' => true,
])

@section('content')
    <span class="eyebrow">Kalkulatori</span>
    <h1 class="title">Kalkulatori za domaću kuhinju</h1>
    <p class="lead">Upišeš količinu, a kalkulator preračuna sve sastojke — bez računanja na papiru i bez „otprilike".</p>

    <div class="grid" style="margin-top:32px;">
        @foreach ($kalkulatori as $slug => $k)
            <a href="/kalkulatori/{{ $slug }}" class="card"><div class="body">
                <div class="ico">{{ $k['ico'] }}</div><h3>{{ $k['naziv'] }}</h3><p>{{ $k['opis'] }}</p>
            </div></a>
        @endforeach
    </div>

    <div class="prose" style="margin-top:40px;max-width:780px;">
        <h2>Zašto računati u postocima?</h2>
        <p>
            Domaći recepti za kobasice, turšiju ili tripice obično se pamte „za jednu svinju" ili „za kantu paprike".
            Kad se količina promijeni, najlakše je pogriješiti upravo u soli. Kalkulatori zato rade s omjerima:
            začini se računaju kao postotak mase mesa, a salamura prema litrama octa — pa je rezultat isti
            bez obzira radiš li 2 kg ili 40 kg.
        </p>
        <p>Uz svaki kalkulator nalazi se i kratki vodič s postupkom i savjetima. Za detaljnije upute pogledaj <a href="/vodici">vodiče</a>.</p>
    </div>
@endsection
