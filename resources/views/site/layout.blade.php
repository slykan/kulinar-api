<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title.' — Kulinar.app' : 'Kulinar.app — recepti, kalkulatori i vodiči za domaću kuhinju' }}</title>
    <meta name="description" content="{{ $description ?? 'Domaći recepti, kalkulatori za kobasice, tripice i turšiju te vodiči za domaću kuhinju.' }}">
    <meta name="robots" content="{{ ($index ?? true) ? 'index, follow' : 'noindex, follow' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $title ?? 'Kulinar.app' }}">
    <meta property="og:description" content="{{ $description ?? '' }}">
    <meta property="og:image" content="{{ $ogImage ?? url('/icons/Icon-512.png') }}">
    <meta property="og:locale" content="hr_HR">
    <meta name="theme-color" content="#181818">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="apple-touch-icon" href="/icons/Icon-192.png">
    <meta name="google-adsense-account" content="ca-pub-3054841074530422">
    @if (($ads ?? false) && ($index ?? true))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3054841074530422" crossorigin="anonymous"></script>
    @endif
    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endisset
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --o: #e85d04; --od: #c44d00; --ol: rgba(232,93,4,.14);
            --bg: #181818; --card: #242424; --card2: #2c2c2c; --line: #333;
            --text: #f2efe9; --muted: #b5aea4; --dim: #8a837a;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif; background: var(--bg); color: var(--text); line-height: 1.65; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        .wrap { max-width: 1120px; margin: 0 auto; padding: 0 20px; }
        .narrow { max-width: 820px; }

        header.top { position: sticky; top: 0; z-index: 50; background: rgba(24,24,24,.9); backdrop-filter: blur(12px); border-bottom: 1px solid var(--line); }
        header.top .wrap { display: flex; align-items: center; justify-content: space-between; height: 64px; gap: 16px; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 900; font-size: 1.15rem; letter-spacing: -.3px; }
        .brand img { width: 34px; height: 34px; border-radius: 9px; }
        .brand span { color: var(--o); }
        nav.main { display: flex; align-items: center; gap: 6px; }
        nav.main a { padding: 8px 14px; border-radius: 9px; color: var(--muted); font-weight: 600; font-size: .95rem; }
        nav.main a:hover, nav.main a.on { color: var(--text); background: var(--card); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 11px; background: var(--o); color: #fff; font-weight: 800; font-size: .95rem; border: 0; cursor: pointer; font-family: inherit; }
        .btn:hover { background: var(--od); }
        .btn.ghost { background: transparent; border: 1.5px solid var(--line); color: var(--text); }
        .btn.ghost:hover { border-color: var(--o); }
        nav.main .btn { margin-left: 8px; padding: 9px 16px; color: #fff; }

        main { padding: 48px 0 72px; }
        .eyebrow { display: inline-block; font-size: .78rem; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; color: var(--o); margin-bottom: 12px; }
        h1.title { font-size: clamp(2rem, 4.6vw, 3rem); font-weight: 900; line-height: 1.12; letter-spacing: -1px; margin-bottom: 14px; }
        .lead { color: var(--muted); font-size: 1.1rem; max-width: 680px; }
        h2.sec { font-size: 1.5rem; font-weight: 850; letter-spacing: -.4px; margin: 56px 0 20px; display: flex; justify-content: space-between; align-items: baseline; gap: 12px; }
        h2.sec a { font-size: .92rem; color: var(--o); font-weight: 700; }

        .grid { display: grid; gap: 18px; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 16px; overflow: hidden; transition: transform .2s, border-color .2s; display: flex; flex-direction: column; }
        a.card:hover { transform: translateY(-3px); border-color: rgba(232,93,4,.5); }
        .card .img { aspect-ratio: 4/3; background: var(--card2) center/cover no-repeat; display: flex; align-items: center; justify-content: center; font-size: 2.6rem; }
        .card .body { padding: 18px 20px 20px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
        .card h3 { font-size: 1.12rem; font-weight: 800; line-height: 1.3; }
        .card p { color: var(--muted); font-size: .93rem; }
        .meta { color: var(--dim); font-size: .84rem; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .stars { color: #ffb020; letter-spacing: 1px; }
        .ico { font-size: 2rem; }

        .prose { font-size: 1.05rem; }
        .prose h2 { font-size: 1.45rem; font-weight: 850; margin: 36px 0 12px; letter-spacing: -.3px; }
        .prose h3 { font-size: 1.15rem; font-weight: 800; margin: 26px 0 8px; color: #ffb27a; }
        .prose p { margin-bottom: 14px; color: #e4dfd7; }
        .prose ul, .prose ol { margin: 0 0 16px 22px; color: #e4dfd7; }
        .prose li { margin-bottom: 7px; }
        .prose strong { color: #fff; }
        .prose a { color: var(--o); font-weight: 700; }
        .note { background: var(--ol); border-left: 3px solid var(--o); border-radius: 10px; padding: 16px 20px; margin: 20px 0; color: #f6d9c4; }
        .warn { background: rgba(255,176,32,.1); border-left: 3px solid #ffb020; border-radius: 10px; padding: 16px 20px; margin: 20px 0; color: #ffe2a8; }
        table.t { width: 100%; border-collapse: collapse; margin: 6px 0 18px; font-size: .97rem; }
        table.t th, table.t td { padding: 11px 12px; border-bottom: 1px solid var(--line); text-align: left; }
        table.t th { color: #ffb27a; font-weight: 700; }
        table.t td.num, table.t th.num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
        table.t tr.total td { font-weight: 800; color: #fff; border-top: 2px solid var(--o); }

        .tool { background: var(--card); border: 1px solid var(--line); border-radius: 18px; padding: 28px; margin: 28px 0; }
        .tool label { display: block; font-size: .85rem; font-weight: 700; color: var(--muted); margin-bottom: 6px; }
        .tool input, .tool select { width: 100%; max-width: 280px; padding: 12px 14px; border-radius: 11px; border: 1.5px solid var(--line); background: var(--bg); color: var(--text); font: inherit; font-size: 1.1rem; }
        .tool input:focus { outline: none; border-color: var(--o); }
        .tool .hint { color: var(--dim); font-size: .84rem; margin-top: 6px; }

        .pill { display: inline-block; padding: 4px 11px; border-radius: 99px; background: var(--ol); color: #ffb27a; font-size: .8rem; font-weight: 700; }
        .crumbs { color: var(--dim); font-size: .9rem; margin-bottom: 18px; }
        .crumbs a:hover { color: var(--o); }
        .cta { margin-top: 48px; background: linear-gradient(135deg, rgba(232,93,4,.22), rgba(232,93,4,.05)); border: 1px solid rgba(232,93,4,.35); border-radius: 18px; padding: 32px; display: flex; gap: 20px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        .cta h3 { font-size: 1.3rem; font-weight: 850; }
        .cta p { color: var(--muted); }
        .sources { color: var(--dim); font-size: .85rem; margin-top: 28px; }

        footer.bot { border-top: 1px solid var(--line); padding: 44px 0 28px; color: var(--dim); font-size: .9rem; }
        footer.bot .cols { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 28px; margin-bottom: 28px; }
        footer.bot h4 { color: var(--text); font-size: .95rem; margin-bottom: 10px; }
        footer.bot li { list-style: none; margin-bottom: 8px; }
        footer.bot a:hover { color: var(--o); }

        @media (max-width: 760px) {
            nav.main a:not(.btn) { display: none; }
            footer.bot .cols { grid-template-columns: 1fr 1fr; }
            .tool { padding: 20px; }
            main { padding-top: 32px; }
        }
        @media print { header.top, footer.bot, .cta, .noprint { display: none !important; } body { background: #fff; color: #000; } }
    </style>
</head>
<body>
<header class="top">
    <div class="wrap">
        <a href="/" class="brand"><img src="/icons/Icon-192.png" alt=""><b>Kulinar<span>.app</span></b></a>
        <nav class="main">
            <a href="/recepti" class="{{ ($nav ?? '') === 'recepti' ? 'on' : '' }}">Recepti</a>
            <a href="/kalkulatori" class="{{ ($nav ?? '') === 'kalkulatori' ? 'on' : '' }}">Kalkulatori</a>
            <a href="/vodici" class="{{ ($nav ?? '') === 'vodici' ? 'on' : '' }}">Vodiči</a>
            <a href="/app/" class="btn">Otvori aplikaciju</a>
        </nav>
    </div>
</header>

<main>
    <div class="wrap {{ $narrow ?? false ? 'narrow' : '' }}">
        @yield('content')
    </div>
</main>

<footer class="bot">
    <div class="wrap">
        <div class="cols">
            <div>
                <a href="/" class="brand" style="margin-bottom:10px;"><img src="/icons/Icon-192.png" alt=""><b>Kulinar<span>.app</span></b></a>
                <p>Domaći recepti zajednice, kalkulatori i vodiči za kobasice, turšiju, tripice i ostale klasike domaće kuhinje.</p>
            </div>
            <div><h4>Sadržaj</h4><ul>
                <li><a href="/recepti">Recepti</a></li>
                <li><a href="/kalkulatori">Kalkulatori</a></li>
                <li><a href="/vodici">Vodiči</a></li>
            </ul></div>
            <div><h4>Kalkulatori</h4><ul>
                <li><a href="/kalkulatori/kobasice">Začini za kobasice</a></li>
                <li><a href="/kalkulatori/tursija">Salamura za turšiju</a></li>
                <li><a href="/kalkulatori/tripice">Tripice</a></li>
            </ul></div>
            <div><h4>Kulinar.app</h4><ul>
                <li><a href="/app/">Aplikacija</a></li>
                <li><a href="/o-nama">O nama</a></li>
                <li><a href="/kontakt">Kontakt</a></li>
                <li><a href="/privatnost">Privatnost</a></li>
            </ul></div>
        </div>
        <p>© {{ date('Y') }} Kulinar.app · Napravljeno u Hrvatskoj</p>
    </div>
</footer>
@stack('scripts')
</body>
</html>
