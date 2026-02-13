<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Admin') - Digitron</title>

  <link rel="stylesheet" href="{{ asset('admin/admin.css') }}">
  <script src="{{ asset('js/jquery.min.js') }}"></script> {{-- keep your jQuery --}}
</head>
<body class="admin-body">

  <aside class="admin-sidebar">
    <div class="brand">DIGITRON <span>ADMIN</span></div>

    <a href="{{ route('admin.dashboard') }}" class="navlink">Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="navlink">Products</a>
    <a href="#" class="navlink disabled">Newsletter (soon)</a>
    <a href="#" class="navlink disabled">Quotes (soon)</a>
  </aside>

  <main class="admin-main">
    <header class="admin-topbar">
      <div class="topbar-left">@yield('topbar')</div>

      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn-ghost">Logout</button>
      </form>
    </header>

    @if(session('status'))
      <div class="toast success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
      <div class="toast error">{{ session('error') }}</div>
    @endif

    <section class="admin-content">
      @yield('content')
    </section>
  </main>

  <script src="{{ asset('admin/admin.js') }}"></script>
</body>
</html>
