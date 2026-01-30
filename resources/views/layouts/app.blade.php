<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', config('app.name'))</title>
  <link rel="icon" href="{{ asset('images/icon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="text-white" data-bg-parallax="1">
  @include('partials.navbar')

  {{-- Full-width area (for hero slider, banners, etc.) --}}
  @yield('fullwidth')

  {{-- Normal page container --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    @yield('content')
  </main>

  @include('partials.footer')
</body>
</html>
