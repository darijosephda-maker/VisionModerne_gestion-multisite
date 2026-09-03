<x-caisse-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __($periodLabel ?? 'Mes ventes du jour') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtres période --}}
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('caisse.mes-ventes', ['periode' => 'jour']) }}"
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ $periode === 'jour' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    📅 Aujourd'hui
                </a>
                <a href="{{ route('caisse.mes-ventes', ['periode' => 'semaine']) }}"
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ $periode === 'semaine' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    📆 Cette semaine
                </a>
                <a href="{{ route('caisse.mes-ventes', ['periode' => 'mois']) }}"
                   class="px-4 py-2 rounded-lg font-semibold text-sm transition
                   {{ $periode === 'mois' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    📊 Ce mois
                </a>
            </div>

            {{-- Bandeau résumé --}}
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl p-6 text-white shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-indigo-100 uppercase tracking-wide font-medium">Total encaissé {{ $periode === 'jour' ? "aujourd'hui" : ($periode === 'semaine' ? 'cette semaine' : 'ce mois') }}</p>
                        <p class="text-4xl font-black mt-1">{{ number_format($total, 0, ',', ' ') }} F</p>
                        <p class="text-sm text-indigo-100 mt-1">{{ now()->translatedFormat('l d F Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-indigo-100">Nombre de transactions</p>
                        <p class="text-2xl font-bold">{{ $ventes->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- Répartition par type --}}
            @php
                $totalProduits = $ventes->where('type', 'produit')->sum('montant');
                $totalUnites = $ventes->where('type', 'unite')->sum('montant');
                $totalWifi = $ventes->where('type', 'wifi')->sum('montant');
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-5 border-l-4 border-emerald-500">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">🛍️ Produits (Secrétariat / Librairie / Boissons / Services)</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ number_format($totalProduits, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-5 border-l-4 border-blue-500">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">📱 Unités télécom</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ number_format($totalUnites, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-5 border-l-4 border-amber-500">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">📶 Forfaits WiFi</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ number_format($totalWifi, 0, ',', ' ') }} F</p>
                </div>
            </div>

            {{-- Détail chronologique --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100">Détail des transactions</h3>
                </div>

                @forelse ($ventes as $vente)
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50 dark:border-slate-700/50 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0
                                {{ $vente['type'] === 'produit' ? 'bg-emerald-100 dark:bg-emerald-900/40' : ($vente['type'] === 'unite' ? 'bg-blue-100 dark:bg-blue-900/40' : 'bg-amber-100 dark:bg-amber-900/40') }}">
                                {{ $vente['type'] === 'produit' ? '🛍️' : ($vente['type'] === 'unite' ? '📱' : '📶') }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800 dark:text-slate-100">{{ $vente['label'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($vente['date'])->format('H:i') }}</p>
                            </div>
                        </div>
                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ number_format($vente['montant'], 0, ',', ' ') }} F</p>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-4xl mb-3">🧾</p>
                        <p class="text-slate-500 dark:text-slate-400">Aucune vente enregistrée pour aujourd'hui.</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Vos ventes apparaîtront ici au fur et à mesure de la journée.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-caisse-layout>
