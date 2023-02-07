<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/app_dark.css'])

</head>

<body>
    <div class="loading">Loading</div>
    <div id="app">
        @include('sections.sidebar')
        <div id="main" class='layout-navbar'>
            @include('sections.header')
            <div id="main-content">
                <div class="page-heading">
                    <x-generals.title title="{{ $title ?? '' }}" subtitle="{{ $subtitle ?? '' }}" :breadcrumb="$breadcrumb" />
                    <section class="section">
                        @yield('content')
                    </section>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/tapp.js'])
    <script>
        const api_key = "{{ session('token') ?? '' }}";
    </script>
    @yield('module')
</body>

</html>
