<div class="cta">
    <div><h3>Kalkulatori i u aplikaciji</h3><p>Kulinar.app ima iste kalkulatore u aplikaciji, uz recepte zajednice koje možeš ocijeniti i spremiti.</p></div>
    <a href="/app/" class="btn">Otvori aplikaciju</a>
</div>
<h2 class="sec">Ostali kalkulatori</h2>
<div class="grid">
    @foreach ($ostali as $slug => $o)
        <a href="/kalkulatori/{{ $slug }}" class="card"><div class="body"><div class="ico">{{ $o['ico'] }}</div><h3>{{ $o['naziv'] }}</h3><p>{{ $o['opis'] }}</p></div></a>
    @endforeach
</div>
