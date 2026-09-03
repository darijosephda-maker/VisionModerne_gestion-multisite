<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: false,
    init() {
        try {
            const enabled = localStorage.getItem('darkMode') === 'true';
            this.darkMode = enabled;
            document.documentElement.classList.toggle('dark', enabled);
            this.$watch('darkMode', value => {
                localStorage.setItem('darkMode', String(value));
                document.documentElement.classList.toggle('dark', value);
            });
        } catch (e) {
            this.darkMode = false;
        }
    }
}" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Vision Moderne Construction SARL — Fournitures, Bureautique & Informatique, Bâtiment & Travaux Publics, Électricité, à Gaoua, Burkina Faso.">
        <title>{{ config('app.name') }} - {{ $title ?? 'Accueil' }}</title>

        <script>
            try {
                if (localStorage.getItem('darkMode') === 'true') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-[#020b1a] text-slate-900 dark:text-slate-100 overflow-x-hidden">
        <div class="min-h-screen flex flex-col bg-slate-50 dark:bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),_transparent_25%),linear-gradient(180deg,#020b1a_0%,#061827_100%)]">

            <header class="sticky top-0 z-30 bg-white/95 dark:bg-[#071827]/95 border-b border-slate-200 dark:border-white/10 backdrop-blur-xl shadow-[0_10px_30px_rgba(0,0,0,0.1)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between gap-4 py-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/90 shadow-lg ring-1 ring-slate-200/80">
                                <img src="{{ asset('images/Logo.jpeg') }}" alt="Vision Moderne Construction" class="h-10 w-10 rounded-lg object-cover">
                            </div>
                            <div class="leading-[1.1]">
                                <div class="text-[15px] md:text-[16px] font-black tracking-[0.03em] text-slate-800 dark:text-slate-100">Vision Moderne</div>
                                <div class="text-[10px] md:text-[11px] font-extrabold tracking-[0.14em]">
                                    <span class="text-emerald-600 dark:text-emerald-400">Construction</span>
                                    <span class="ml-1 text-slate-600 dark:text-slate-300">SARL</span>
                                </div>
                            </div>
                        </a>

                        <div class="hidden md:flex items-center gap-6 text-sm text-slate-600 dark:text-slate-300">
                            <div x-data="{ now: new Date(), init() { setInterval(() => this.now = new Date(), 1000) } }" x-cloak class="text-slate-500 dark:text-slate-400">
                                <span x-text="now.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })"></span>
                                <span class="mx-2">•</span>
                                <span x-text="now.toLocaleTimeString('fr-FR')"></span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button @click="darkMode = !darkMode" x-cloak class="hidden sm:flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 bg-slate-100 text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 transition hover:bg-slate-200 dark:hover:bg-white/10">
                                <svg x-show="!darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>

                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-500">
                                    Mon espace
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-500">
                                    Connexion
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <nav class="border-t border-slate-200 dark:border-white/10 bg-slate-100/50 dark:bg-[#0b1d2c]/70">
                    <div class="max-w-5xl mx-auto flex items-center justify-center gap-8 sm:gap-12">
                        <a href="{{ route('home') }}" class="py-4 text-base font-semibold transition {{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-500' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                            Accueil
                        </a>
                        <a href="{{ route('apropos') }}" class="py-4 text-base font-semibold transition {{ request()->routeIs('apropos') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-500' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                            À propos
                        </a>
                        <a href="{{ route('contact') }}" class="py-4 text-base font-semibold transition {{ request()->routeIs('contact') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-500' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }}">
                            Contact
                        </a>
                    </div>
                </nav>
            </header>

            <main class="flex-1 py-8 md:py-10">
                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 dark:border-white/10 bg-slate-100/50 dark:bg-[#071827]/90">
                <div class="max-w-7xl mx-auto px-4 py-5 text-center text-slate-600 dark:text-slate-300 sm:px-6 lg:px-8">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">VISION MODERNE CONSTRUCTION SARL</p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        📞 73 30 43 45 / 06 06 06 46 • ✉️ visionmoderneconstructionsarl@gmail.com • 📍 Secteur 4, Gaoua - Poni - Burkina Faso
                    </p>
                </div>
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>