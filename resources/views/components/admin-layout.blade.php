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
            document.documentElement.classList.remove('dark');
        }
    }
}" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Administration</title>

        <script>
            try {
                const enabled = localStorage.getItem('darkMode') === 'true';
                document.documentElement.classList.toggle('dark', enabled);
            } catch (e) {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100">
        <div x-data="{ darkMode: false, sidebarOpen: false, init() { try { const enabled = localStorage.getItem('darkMode') === 'true'; this.darkMode = enabled; document.documentElement.classList.toggle('dark', enabled); this.$watch('darkMode', value => { localStorage.setItem('darkMode', String(value)); document.documentElement.classList.toggle('dark', value); }); } catch (e) { this.darkMode = false; document.documentElement.classList.remove('dark'); } }, toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; } }" :class="{ 'dark': darkMode }" class="min-h-screen bg-slate-100 dark:bg-slate-950">

            <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-slate-900/40 lg:hidden"></div>

            {{-- SIDEBAR FIXE --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 w-64 bg-slate-200/95 dark:bg-slate-900/95 border-r border-slate-300 dark:border-slate-700 flex flex-col z-30 shadow-sm transition-transform duration-200 ease-in-out lg:translate-x-0">
                <div class="h-20 flex items-center gap-3 px-4 border-b border-slate-300 dark:border-slate-700 shrink-0 bg-slate-200 dark:bg-slate-900">
                    <img src="{{ asset('images/Logo.jpeg') }}" alt="Vision Moderne Construction" class="h-16 w-auto rounded-lg object-contain shadow-sm ring-1 ring-slate-200 dark:ring-slate-700">
                    <div class="leading-[1.1]">
                        <div class="text-[18px] font-black tracking-[0.04em] text-slate-800 dark:text-slate-100">Vision Moderne</div>
                        <div class="mt-0.5 text-[13px] font-extrabold tracking-[0.18em]">
                            <span class="text-emerald-600 dark:text-emerald-400">Construction</span>
                            <span class="ml-1 text-slate-600 dark:text-slate-300">SARL</span>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto bg-slate-200/90 dark:bg-slate-900/90">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>📊</span> Tableau de bord
                    </a>

                    <a href="{{ route('caisse.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800">
                        <span>🧾</span> Accéder à la caisse
                    </a>

                    <p class="px-3 pt-4 pb-1 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Modules</p>

                    <a href="{{ route('admin.produits.index', ['module' => 'secretariat']) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.produits.*') && request()->get('module') === 'secretariat' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>🗂️</span> Secrétariat
                    </a>

                    <a href="{{ route('admin.produits.index', ['module' => 'librairie']) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.produits.*') && request()->get('module') === 'librairie' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>📚</span> Librairie
                    </a>

                    <a href="{{ route('admin.produits.index', ['module' => 'boissons']) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.produits.*') && request()->get('module') === 'boissons' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>🥤</span> Boissons
                    </a>

                    <a href="{{ route('admin.type-services.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.type-services.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>🖨️</span> Services
                    </a>

                    <a href="{{ route('admin.stocks-unites.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.stocks-unites.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>📶</span> Unités & WiFi
                    </a>

                    <p class="px-3 pt-4 pb-1 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Gestion</p>

                    <a href="{{ route('admin.fournisseurs.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.fournisseurs.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>🚚</span> Fournisseurs
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>👥</span> Utilisateurs
                    </a>
                    <a href="{{ route('admin.rapports.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.rapports.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>📄</span> Rapports
                    </a>
                    <a href="{{ route('admin.factures.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.factures.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>🧾</span> Factures
                    </a>
                    <a href="{{ route('admin.site-contenu.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium
                        {{ request()->routeIs('admin.site-contenu.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span>⚙️</span> Contenu du site
                    </a>
                </nav>

                <div class="p-3 border-t border-slate-300 dark:border-slate-700 shrink-0 bg-slate-200 dark:bg-slate-900">
                    <div class="flex items-center gap-2 px-2 py-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-300 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('profile') }}"
                       class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-sm font-medium transition {{ request()->routeIs('profile') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        <span aria-hidden="true">👤</span> Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full mt-1 text-start px-2 py-2 rounded-md text-sm text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/30">
                            🚪 Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ZONE PRINCIPALE (décalée de la largeur de la sidebar) --}}
            <div class="lg:ml-64 flex flex-col min-h-screen">

                {{-- HEADER FIXE --}}
                <header class="sticky top-0 z-20 bg-slate-200/95 dark:bg-slate-900/95 shadow-sm border-b border-slate-300 dark:border-slate-700 backdrop-blur-sm">
                    <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <button type="button" @click="sidebarOpen = !sidebarOpen" class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-md border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="text-slate-800 dark:text-slate-100 truncate">
                                {{ $header ?? '' }}
                            </div>
                        </div>

                        <div class="flex items-center gap-4 shrink-0">
                            {{-- Horloge dynamique --}}
                            <div x-data="{
                                now: new Date(),
                                init() {
                                    setInterval(() => this.now = new Date(), 1000);
                                }
                            }"
                                x-cloak
                                class="hidden md:block text-sm text-slate-600 dark:text-slate-300 text-right">
                                <span x-text="now.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })"></span>
                                <span class="mx-1">-</span>
                                <span x-text="now.toLocaleTimeString('fr-FR')"></span>
                            </div>

                            {{-- Bouton dark mode --}}
                            <button @click="darkMode = !darkMode" x-cloak class="p-2 rounded-md text-slate-700 hover:bg-slate-300 dark:text-slate-200 dark:hover:bg-slate-800 transition">
                                <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                {{-- CONTENU --}}
                <main class="flex-1 pb-32 bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
                    {{ $slot }}
                </main>

            </div>

            {{-- FOOTER FIXE --}}
            <footer class="fixed bottom-0 left-0 right-0 z-20 bg-slate-200/95 dark:bg-slate-900/95 border-t border-slate-300 dark:border-slate-700 backdrop-blur-sm lg:left-64">
                <div class="px-3 sm:px-6 py-3 sm:py-4 flex flex-col items-center text-center gap-2">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">VISION MODERNE CONSTRUCTION SARL</p>

                    <nav class="flex flex-wrap justify-center gap-x-5 gap-y-1 text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Tableau de bord</a>
                        <a href="{{ route('caisse.index') }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Caisse</a>
                        <a href="{{ route('admin.produits.index', ['module' => 'secretariat']) }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Secrétariat</a>
                        <a href="{{ route('admin.produits.index', ['module' => 'librairie']) }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Librairie</a>
                        <a href="{{ route('admin.produits.index', ['module' => 'boissons']) }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Boissons</a>
                        <a href="{{ route('admin.produits.index', ['module' => 'services']) }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Services</a>
                        <a href="{{ route('admin.stocks-unites.index') }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Unités & WiFi</a>
                        <a href="{{ route('admin.fournisseurs.index') }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Fournisseurs</a>
                        <a href="{{ route('admin.users.index') }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Utilisateurs</a>
                        <a href="{{ route('admin.rapports.index') }}" class="text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400">Rapports</a>
                    </nav>

                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        📞 73 30 43 45 / 06 06 06 46 &nbsp;•&nbsp; ✉️ visionmoderneconstructionsarl@gmail.com &nbsp;•&nbsp; 📍 Secteur 4, Gaoua - Poni - Burkina Faso
                    </p>

                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        IFU: 00266456E &nbsp;•&nbsp; RCCM: BF-GAO-01-2025-B13-00080 &nbsp;•&nbsp; &copy; {{ now()->year }} Vision Moderne Construction SARL. Tous droits réservés.
                    </p>
                </div>
            </footer>

        </div>

        @stack('scripts')
    </body>
</html>