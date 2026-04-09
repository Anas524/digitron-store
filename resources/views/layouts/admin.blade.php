<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title','Admin') - Digitron</title>
  <link rel="icon" href="{{ asset('images/icon.png') }}">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;900&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Tailwind -->
  @vite(['resources/css/app.css','resources/js/app.js'])

  @stack('styles')

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    .font-display {
      font-family: 'Orbitron', sans-serif;
    }

    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.02);
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.10);
      border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #00f0ff;
    }

    * {
      transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
      transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
      transition-duration: 150ms;
    }

    .sidebar-link {
      position: relative;
      overflow: hidden;
    }

    .sidebar-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background: var(--admin-primary);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .sidebar-link:hover::before,
    .sidebar-link.active::before {
      transform: scaleY(1);
    }

    /* Admin Custom Styles */
    :root {
      --admin-primary: #00f0ff;
      --admin-secondary: #7000ff;
      --admin-success: #00ff88;
      --admin-warning: #ffaa00;
      --admin-danger: #ff2d55;
      --admin-bg: #050508;
      --admin-panel: #0a0a0f;
    }

    .admin-gradient-text {
      background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .glass-panel {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .neon-glow {
      box-shadow: 0 0 20px rgba(0, 240, 255, 0.15),
        0 0 40px rgba(0, 240, 255, 0.1),
        0 0 60px rgba(0, 240, 255, 0.05);
    }

    .sidebar-link {
      position: relative;
      overflow: hidden;
    }

    .sidebar-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background: var(--admin-primary);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .sidebar-link:hover::before,
    .sidebar-link.active::before {
      transform: scaleY(1);
    }

    .stat-card {
      position: relative;
      overflow: hidden;
    }

    .stat-card::after {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(0, 240, 255, 0.1) 0%, transparent 70%);
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .stat-card:hover::after {
      opacity: 1;
    }

    .chart-container {
      position: relative;
    }

    .chart-line {
      stroke-dasharray: 1000;
      stroke-dashoffset: 1000;
      animation: drawLine 2s ease forwards;
    }

    @keyframes drawLine {
      to {
        stroke-dashoffset: 0;
      }
    }

    .pulse-ring {
      position: absolute;
      border-radius: 50%;
      border: 2px solid var(--admin-success);
      animation: pulseRing 2s ease-out infinite;
    }

    @keyframes pulseRing {
      0% {
        transform: scale(1);
        opacity: 1;
      }

      100% {
        transform: scale(1.5);
        opacity: 0;
      }
    }

    .floating-element {
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    .grid-bg {
      background-image:
        linear-gradient(rgba(0, 240, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 240, 255, 0.03) 1px, transparent 1px);
      background-size: 50px 50px;
    }

    .activity-item {
      transition: all 0.3s ease;
    }

    .activity-item:hover {
      transform: translateX(10px);
      background: rgba(255, 255, 255, 0.05);
    }

    .progress-ring {
      transform: rotate(-90deg);
    }

    .progress-ring-circle {
      transition: stroke-dashoffset 1s ease;
    }
  </style>
</head>

<body class="bg-[#070b14] text-white antialiased h-screen overflow-hidden" data-no-lenis="1">
  <div
    x-data="{ sidebarOpen: false }"
    @keydown.escape.window="sidebarOpen = false"
    class="relative flex h-screen overflow-hidden"
  >
    @include('admin.partials.sidebar')

    <main
      class="relative flex-1 min-w-0 h-screen overflow-hidden"
      @click="if (window.innerWidth < 1024 && sidebarOpen) sidebarOpen = false"
    >
      <div class="@yield('adminContentClass', 'p-6 h-full overflow-y-auto') admin-main-scroll">
        @yield('content')
      </div>
    </main>
  </div>
</body>

</html>