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
    @include('material.templates.auth')
    @include('material.templates.workspace')
    @include('material.templates.pages.overview')
    @include('material.templates.pages.transactions')
    @include('material.templates.pages.users')
    @include('material.templates.pages.categories')
    @include('material.templates.pages.tasks')
    @include('material.templates.pages.exchange')
    <div id="finance-app"><div class="startup" role="status">Завантаження Finance…</div></div>
    <noscript>Для роботи кабінету увімкніть JavaScript у браузері.</noscript>
    <script defer src="{{ asset('material/vue.global.prod.js') }}"></script>
    <script defer src="{{ asset('material/domain.js') }}?v={{ filemtime(public_path('material/domain.js')) }}"></script>
    <script defer src="{{ asset('material/application.js') }}?v={{ filemtime(public_path('material/application.js')) }}"></script>
    <script defer src="{{ asset('material/app.js') }}?v={{ filemtime(public_path('material/app.js')) }}-{{ md5_file(resource_path('views/material/templates/auth.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/workspace.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/pages/overview.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/pages/transactions.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/pages/users.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/pages/categories.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/pages/tasks.blade.php')) }}-{{ md5_file(resource_path('views/material/templates/pages/exchange.blade.php')) }}"></script>
</body>
</html>
