@extends('site.layout', [
    'title' => 'Vodiči za domaću kuhinju',
    'description' => 'Vodiči korak po korak: domaće kobasice, turšija i salamura, tripice, kuhinjske mjere i preračunavanje recepata.',
    'nav' => 'vodici',
    'ads' => false, // list page — ads only next to recipes, guides and calculators
])

@section('content')
    <span class="eyebrow">Vodiči</span>
    <h1 class="title">Vodiči za domaću kuhinju</h1>
    <p class="lead">Postupci korak po korak za klasike domaće kuhinje — s omjerima, savjetima i greškama koje treba izbjeći.</p>
    <div class="grid" style="margin-top:32px;">
        @foreach ($vodici as $v)
            <a href="/vodici/{{ $v['slug'] }}" class="card"><div class="body">
                <span class="pill" style="align-self:flex-start;">{{ $v['read'] }} čitanja</span>
                <h3>{{ $v['title'] }}</h3><p>{{ $v['excerpt'] }}</p>
            </div></a>
        @endforeach
    </div>
@endsection
