<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Anggrek Foods') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Logo.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                DEFAULT: '#f59e0b',
                                light: '#fbbf24',
                                softer: '#fef3c7',
                                dark: '#b45309'
                            }
                        }
                    }
                }
            };
        </script>
    @endif
</head>
<body class="min-h-screen bg-amber-50 text-gray-900 flex flex-col">
    <header class="text-gray-900 shadow-lg" style="background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-85 transition">
                <img src="{{ asset('Logo.png') }}" alt="Anggrek Foods" class="w-10 h-10">
                <h1 class="text-xl font-bold">Anggrek Foods</h1>
            </a>
            <nav class="flex items-center gap-3 text-sm font-medium">
                @guest
                    <a href="{{ route('login') }}"
                    class="px-4 py-2 rounded-lg bg-white/95 text-gray-800 font-semibold hover:bg-white shadow-sm border border-yellow-300">
                        Login
                    </a>
                @endguest

                @auth
                    <a href="{{ route('portfolio') }}"
                    class="px-4 py-2 rounded-lg bg-white/90 text-amber-900 font-semibold hover:bg-white shadow-sm border border-amber-200">
                        Portfolio
                    </a>

                    <a href="{{ route('hpp.index') }}"
                    class="px-4 py-2 rounded-lg bg-white/95 text-gray-800 font-semibold hover:bg-white shadow-sm border border-yellow-300">
                        Riwayat HPP
                    </a>

                    <a href="{{ route('sales.index') }}"
                    class="px-4 py-2 rounded-lg bg-white/95 text-gray-800 font-semibold hover:bg-white shadow-sm border border-yellow-300">
                        Penjualan
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-4 py-2 rounded-lg bg-white/95 text-gray-800 font-semibold hover:bg-white shadow-sm border border-yellow-300">
                            Logout
                        </button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl px-4 py-8 flex-1">
        @yield('content')
    </main>

    <footer class="mt-auto border-t bg-white/90 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 py-6 text-sm text-gray-600">© {{ date('Y') }} Anggrek Foods — HPP Calculator</div>
    </footer>
</body>
</html>
