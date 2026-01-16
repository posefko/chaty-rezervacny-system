<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Rezervačný systém pre horské chaty')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col theme-light">

{{-- NAVBAR --}}
<header class="bg-white/80 backdrop-blur border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

        {{-- LOGO --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white font-bold">
                CC
            </span>
            <span class="font-semibold text-lg">CabinConnect</span>
        </a>

        {{-- DESKTOP MENU --}}
        <nav class="hidden md:flex items-center gap-4 text-sm">
            <a href="{{ url('/') }}" class="hover:text-emerald-600">Domov</a>
            <a href="{{ url('/chaty') }}" class="hover:text-emerald-600">Chaty</a>
            <a href="{{ url('/rezervacie') }}" class="hover:text-emerald-600">Moje rezervácie</a>
            <a href="{{ url('/o-nas') }}" class="hover:text-emerald-600">O nás</a>
            <a href="{{ url('/kontakt') }}" class="hover:text-emerald-600">Kontakt</a>
        </nav>

        {{-- RIGHT ACTIONS --}}
        <div class="flex items-center gap-2">

            {{-- AUTH LINKS --}}
            @guest
                <a href="{{ url('/login') }}" class="hidden sm:inline-block btn-outline text-xs">
                    Prihlásenie
                </a>
                <a href="{{ url('/register') }}" class="hidden sm:inline-block btn-primary text-xs">
                    Registrácia
                </a>
            @endguest

            @auth
                <div class="hidden sm:flex items-center gap-2 text-xs">
        <span class="text-slate-600">
            {{ auth()->user()->name }}
        </span>

                    @if(auth()->user()->is_admin)
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                ADMIN
            </span>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-outline text-xs">
                            Odhlásiť
                        </button>
                    </form>
                </div>
            @endauth


            {{-- THEME TOGGLE (tvoj existujúci) --}}
            <button
                id="theme-toggle"
                type="button"
                class="inline-flex items-center justify-center h-9 w-9 rounded-full border border-slate-300 text-slate-700 text-sm"
                title="Zmeniť tému"
            >
                ☀️
            </button>

            {{-- HAMBURGER (mobile) --}}
            <button
                id="mobile-menu-toggle"
                type="button"
                class="md:hidden inline-flex items-center justify-center h-9 w-9 rounded-full border border-slate-300 text-slate-700 text-lg"
                aria-expanded="false"
                aria-controls="mobile-menu"
                title="Menu"
            >
                ☰
            </button>
        </div>
    </div>

    {{-- MOBILE MENU (dropdown) --}}
    <nav id="mobile-menu" class="md:hidden hidden border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-2 text-sm">
            <a href="{{ url('/') }}" class="py-1 hover:text-emerald-600">Domov</a>
            <a href="{{ url('/chaty') }}" class="py-1 hover:text-emerald-600">Chaty</a>
            <a href="{{ url('/rezervacie') }}" class="py-1 hover:text-emerald-600">Moje rezervácie</a>
            <a href="{{ url('/o-nas') }}" class="py-1 hover:text-emerald-600">O nás</a>
            <a href="{{ url('/kontakt') }}" class="py-1 hover:text-emerald-600">Kontakt</a>

            @guest
                <div class="pt-2 flex gap-2">
                    <a href="{{ url('/login') }}"
                       class="text-xs px-3 py-1 rounded border border-emerald-500 text-emerald-600 hover:bg-emerald-50">
                        Prihlásenie
                    </a>
                    <a href="{{ url('/register') }}"
                       class="text-xs px-3 py-1 rounded bg-emerald-500 text-white hover:bg-emerald-600">
                        Registrácia
                    </a>
                </div>
            @endguest

            @auth
                <div class="pt-2 flex items-center justify-between gap-2">
                    <div class="text-xs">
                        <span class="text-slate-600">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->is_admin)
                            <span class="ml-2 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                    ADMIN
                </span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-xs px-3 py-1 rounded border border-slate-300 text-slate-700 hover:bg-slate-50">
                            Odhlásiť
                        </button>
                    </form>
                </div>
            @endauth

        </div>
    </nav>
</header>


{{-- HLAVNÝ OBSAH --}}
<main class="flex-1">
    <div class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </div>
</main>

{{-- FOOTER --}}
<footer class="bg-white border-t border-slate-200 dark:bg-slate-800 dark:border-slate-700">
    <div class="max-w-6xl mx-auto px-4 py-4 text-center text-xs text-slate-500 dark:text-slate-400">
        © {{ date('Y') }} Rezervačný systém pre horské chaty – CabinConnect.
    </div>
</footer>

</body>
</html>
