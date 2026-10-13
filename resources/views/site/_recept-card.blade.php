@php($img = \App\Http\Controllers\SiteController::slika($p->image))
<a href="/recepti/{{ $p->slug }}" class="card">
    <div class="img" @if ($img) style="background-image:url('{{ $img }}')" @endif>@unless ($img)🍽️@endunless</div>
    <div class="body">
        <h3>{{ $p->title }}</h3>
        <p>{{ \App\Http\Controllers\SiteController::sazetak($p, 110) }}</p>
        <div class="meta" style="margin-top:auto;padding-top:8px;">
            @if ($p->rating_count > 0)
                <span class="stars">★ {{ number_format($p->rating_average, 1, ',', '') }}</span><span>({{ $p->rating_count }})</span>
            @endif
            @if ($p->servings)<span>{{ $p->servings }} os.</span>@endif
            <span>{{ $p->user?->name }}</span>
        </div>
    </div>
</a>
