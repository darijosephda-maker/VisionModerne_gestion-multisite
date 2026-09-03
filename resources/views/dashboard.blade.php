<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord - Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Secrétariat</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">0 F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Librairie</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">0 F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Boissons</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">0 F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unités &amp; WiFi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">0 F</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <p class="text-gray-900 dark:text-gray-100">
                    Bienvenue, {{ auth()->user()->name }}. Le tableau de bord détaillé (graphiques, alertes stock, rapports) sera construit progressivement à chaque module.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>