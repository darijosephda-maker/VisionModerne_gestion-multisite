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

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script>
            try {
                const enabled = localStorage.getItem('darkMode') === 'true';
                document.documentElement.classList.toggle('dark', enabled);
            } catch (e) {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            </div>

        @stack('scripts')
    </body>
</html>
