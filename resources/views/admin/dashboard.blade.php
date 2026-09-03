<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 {{ __('Tableau de bord - Administration') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">

            @php
                $heure = now()->hour;
                if ($heure < 12) {
                    $salutation = 'Bonjour';
                    $icone = '☀️';
                } elseif ($heure < 18) {
                    $salutation = 'Bon après-midi';
                    $icone = '🌤️';
                } else {
                    $salutation = 'Bonsoir';
                    $icone = '🌙';
                }
            @endphp

            <div class="rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-5 sm:p-6 text-white shadow-xl ring-1 ring-indigo-400/30">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-lg font-semibold tracking-wide">{{ $icone }} {{ $salutation }}, {{ explode(' ', auth()->user()->name)[0] }} !</p>
                        <p class="mt-1 text-sm text-indigo-100">Voici un aperçu de l'activité de Vision Moderne Construction aujourd'hui, {{ now()->translatedFormat('l d F Y') }}.</p>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-indigo-50 ring-1 ring-white/15">
                        Système pro
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center">
                    <span class="shrink-0 bg-amber-500 text-white text-xs font-bold px-3 py-2 flex items-center gap-1">
                        📢 INFO
                    </span>
                    <div class="relative flex-1 overflow-hidden whitespace-nowrap py-2">
                        <div class="inline-block animate-marquee text-sm text-gray-700 dark:text-gray-300">
                            Bienvenue sur le système de gestion Vision Moderne Construction SARL &nbsp;•&nbsp;
                            Pensez à vérifier les alertes de stock régulièrement &nbsp;•&nbsp;
                            Toute suppression de données est définitive et tracée dans le journal d'audit &nbsp;•&nbsp;
                            Pour toute assistance, contactez l'administrateur système &nbsp;•&nbsp;
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 border-l-4 border-blue-500 ring-1 ring-slate-100 dark:ring-slate-700 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Secrétariat (jour)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($caSecretariat, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 border-l-4 border-emerald-500 ring-1 ring-slate-100 dark:ring-slate-700 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Librairie (jour)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($caLibrairie, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 border-l-4 border-amber-500 ring-1 ring-slate-100 dark:ring-slate-700 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Boissons (jour)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($caBoissons, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 border-l-4 border-purple-500 ring-1 ring-slate-100 dark:ring-slate-700 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Bénéfice Unités/WiFi (jour)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($beneficeUnitesWifi, 0, ',', ' ') }} F</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 border-l-4 border-slate-500 ring-1 ring-slate-100 dark:ring-slate-700 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Total ce mois</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($caMois, 0, ',', ' ') }} F</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Évolution des ventes (7 derniers jours)</h3>
                    <canvas id="ventesChart" height="90"></canvas>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Produits les plus vendus</h3>
                    @forelse ($produitsPlusVendus as $ligne)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $ligne->produit->nom ?? 'Produit supprimé' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $ligne->produit->module ?? '-' }}</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ligne->total_vendu }} vendus</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune vente enregistrée pour le moment.</p>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="text-amber-500">⚠</span> Alertes stock bas
                    </h3>
                    @forelse ($produitsStockBas as $produit)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $produit->nom }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $produit->module }}</p>
                            </div>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $produit->quantite_stock }} restant(s)</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune alerte de stock pour le moment.</p>
                    @endforelse
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="text-amber-500">⚠</span> Alertes capital Unités télécom
                    </h3>
                    @forelse ($stocksUnitesBas as $stock)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $stock->operateur }}</p>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ number_format($stock->solde_actuel, 0, ',', ' ') }} F restant</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune alerte de capital pour le moment.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('ventesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labelsJours),
                datasets: [{
                    label: 'Ventes (F)',
                    data: @json($dataVentes),
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    @endpush
</x-admin-layout>