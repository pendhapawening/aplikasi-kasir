<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CafePOS Pro') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .coffee-bg {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 50%, #F4A460 100%);
        }
        .sidebar-bg {
            background: #2D1810;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @if(auth()->check())
        <div class="sidebar-bg text-white w-64 min-h-screen p-4 hidden md:block">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-amber-400">CafePOS Pro</h1>
                <p class="text-sm text-gray-300">Sistem POS Cafe</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-600 transition {{ request()->routeIs('dashboard') ? 'bg-amber-600' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('pos.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-600 transition {{ request()->routeIs('pos.*') ? 'bg-amber-600' : '' }}">
                    <i class="fas fa-cash-register mr-2"></i>POS
                </a>
                <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-600 transition {{ request()->routeIs('products.*') ? 'bg-amber-600' : '' }}">
                    <i class="fas fa-box mr-2"></i>Produk
                </a>
                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-600 transition {{ request()->routeIs('transactions.*') ? 'bg-amber-600' : '' }}">
                    <i class="fas fa-receipt mr-2"></i>Transaksi
                </a>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
                <a href="{{ route('reports.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-600 transition {{ request()->routeIs('reports.*') ? 'bg-amber-600' : '' }}">
                    <i class="fas fa-chart-bar mr-2"></i>Laporan
                </a>
                @endif
            </nav>

            <div class="absolute bottom-4 left-4 right-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Bar -->
            @if(auth()->check())
            <div class="bg-white shadow-sm px-4 py-3 md:hidden">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-amber-600">CafePOS Pro</h1>
                    <button class="text-gray-600" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div id="mobile-menu" class="hidden mt-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-100">Dashboard</a>
                    <a href="{{ route('pos.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-100">POS</a>
                    <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-100">Produk</a>
                    <a href="{{ route('transactions.index') }}" class="block px-4 py-2 rounded-lg hover:bg-amber-100">Transaksi</a>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            <main class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
