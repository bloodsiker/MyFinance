<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#e8f3ff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance — особистий кабінет</title>
    <link rel="stylesheet" href="{{ asset('material/app.css') }}?v={{ filemtime(public_path('material/app.css')) }}">
</head>
<body>
    <script id="finance-bootstrap" type="application/json">@json($bootstrap, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
    <div id="finance-app"><div class="startup" role="status">Завантаження Finance…</div></div>
    <noscript>Для роботи кабінету увімкніть JavaScript у браузері.</noscript>
    <script defer src="{{ asset('material/vue.global.prod.js') }}"></script>
    <script defer src="{{ asset('material/domain.js') }}?v={{ filemtime(public_path('material/domain.js')) }}"></script>
    <script defer src="{{ asset('material/app.js') }}?v={{ filemtime(public_path('material/app.js')) }}"></script>
</body>
</html>
