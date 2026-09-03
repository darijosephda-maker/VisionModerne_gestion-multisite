<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Connexion</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased overflow-hidden m-0">
        <div class="h-screen flex items-center justify-center px-4 py-3 bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.15),_transparent_35%),linear-gradient(135deg,#f8fafc_0%,#eef2ff_40%,#ecfeff_100%)] dark:bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.25),_transparent_30%),linear-gradient(135deg,#020817_0%,#0f172a_35%,#111827_100%)]">

            <div class="w-full max-w-[430px]">

                {{-- Logo + nom entreprise --}}
                <div class="flex flex-col items-center mb-4">
                    <a href="/" class="flex flex-col items-center gap-2">
                        <div class="rounded-2xl bg-white/80 p-2 shadow-lg ring-1 ring-slate-200/80 backdrop-blur-sm dark:bg-slate-900/70 dark:ring-slate-700/80">
                            <img src="{{ asset('images/Logo.jpeg') }}" alt="Vision Moderne Construction"
                                 class="h-16 w-auto rounded-xl">
                        </div>
                        <div class="text-center leading-tight">
                            <div class="text-base font-black tracking-wide text-slate-800 dark:text-slate-100">Vision Moderne</div>
                            <div class="text-[11px] font-extrabold tracking-[0.18em]">
                                <span class="text-emerald-600 dark:text-emerald-400">Construction</span>
                                <span class="ml-1 text-slate-600 dark:text-slate-300">SARL</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Carte de connexion --}}
                <div class="bg-white/90 dark:bg-slate-900/90 shadow-[0_20px_60px_rgba(15,23,42,0.12)] ring-1 ring-slate-200/80 dark:ring-slate-700/80 rounded-[28px] px-4 py-4 sm:px-5 sm:py-5 backdrop-blur-sm">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <p class="text-center text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-3">
                    &copy; {{ now()->year }} Vision Moderne Construction SARL — Espace sécurisé
                </p>

            </div>

        </div>
    </body>
</html>