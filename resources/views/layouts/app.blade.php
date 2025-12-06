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
                CH
            </span>
            <span class="font-semibold text-lg">Horské chaty</span>
        </a>

        {{-- DESKTOP MENU --}}
        <nav class="hidden md:flex items-center gap-4 text-sm">
            <a href="{{ url('/') }}" class="hover:text-emerald-600">Domov</a>
            <a href="{{ url('/chaty') }}" class="hover:text-emerald-600">Chaty</a>
            <a href="{{ url('/moje-rezervacie') }}" class="hover:text-emerald-600">Moje rezervácie</a>
            <a href="{{ url('/o-nas') }}" class="hover:text-emerald-600">O nás</a>
            <a href="{{ url('/kontakt') }}" class="hover:text-emerald-600">Kontakt</a>
        </nav>

        {{-- RIGHT ACTIONS --}}
        <div class="flex items-center gap-2">

            {{-- auth linky zatiaľ necháme --}}
            <a href="{{ url('/login') }}" class="hidden sm:inline-block btn-outline text-xs">
                Prihlásenie
            </a>
            <a href="{{ url('/register') }}" class="hidden sm:inline-block btn-primary text-xs">
                Registrácia
            </a>

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
            <a href="{{ url('/moje-rezervacie') }}" class="py-1 hover:text-emerald-600">Moje rezervácie</a>
            <a href="{{ url('/o-nas') }}" class="py-1 hover:text-emerald-600">O nás</a>
            <a href="{{ url('/kontakt') }}" class="py-1 hover:text-emerald-600">Kontakt</a>

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

{{-- netriviálny JS – prepínač dark/light --}}
<script>
    (function () {
        const body = document.body;
        const btn = document.getElementById('theme-toggle');
        if (!btn) return;

        // Načítaj tému z localStorage
        const stored = localStorage.getItem('theme');
        if (stored === 'dark') {
            body.classList.remove('theme-light');
            body.classList.add('theme-dark');
        } else {
            body.classList.remove('theme-dark');
            body.classList.add('theme-light');
        }

        const updateIcon = () => {
            const isDark = body.classList.contains('theme-dark');
            btn.textContent = isDark ? '🌙' : '☀️';
        };

        updateIcon();

        btn.addEventListener('click', () => {
            const isDark = body.classList.toggle('theme-dark');
            body.classList.toggle('theme-light', !isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcon();
        });

        // ===== MOBILE MENU TOGGLE =====
        const toggle = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');

        if (toggle && menu) {
            toggle.addEventListener('click', () => {
                const isHidden = menu.classList.contains('hidden');
                menu.classList.toggle('hidden', !isHidden);
                toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });

            // Voliteľné: zavrie menu po kliknutí na link
            menu.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
        }
    })();
</script>

</body>
</html>
