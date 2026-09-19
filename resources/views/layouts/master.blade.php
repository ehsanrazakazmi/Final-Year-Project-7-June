<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Resident Portal') &middot; {{ config('app.name') }}</title>

    {{-- This layout used to load resources/sass/app.scss, which contains only
         Bootstrap and a font - none of the resident page styles. Every stylesheet
         written for these pages (pages/home, cart, checkout, product, accounts,
         success, and partials/_nav) is imported by resources/scss/app.scss, so
         the portal rendered essentially unstyled.
         The old <link> to asset('css/app.css') is dropped: that file is 2 bytes. --}}
    @vite(['resources/scss/app.scss','resources/js/app.js'])
</head>
<body class="resident-portal">
    @include('layouts.partials.nav')

    <main class="page">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
</body>
</html>
