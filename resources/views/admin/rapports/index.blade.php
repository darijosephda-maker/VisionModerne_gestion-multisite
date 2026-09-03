<style>
    .print-button {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 0.8rem 1.3rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 12px 25px rgba(79, 70, 229, 0.28);
        transition: all 0.2s ease;
    }

    .print-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(79, 70, 229, 0.35);
    }

    .stat-card {
        border-radius: 1.25rem;
        border: 1px solid rgba(148, 163, 184, 0.15);
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .dark .stat-card {
        background: linear-gradient(180deg, rgba(30,41,59,0.96), rgba(15,23,42,0.96));
        border-color: rgba(148,163,184,0.12);
    }

    .filter-panel {
        background: linear-gradient(135deg, rgba(255,255,255,0.98), rgba(248,250,252,0.94));
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.04);
    }

    .dark .filter-panel {
        background: linear-gradient(135deg, rgba(15,23,42,0.96), rgba(30,41,59,0.95));
        border-color: rgba(148,163,184,0.12);
    }

    @media print {
        body {
            background: white;
        }

        .no-print {
            display: none !important;
        }

        .max-w-7xl {
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .bg-white,
        .bg-gray-50,
        .bg-gray-100,
        .dark:bg-gray-800,
        .dark:bg-gray-700,
        .stat-card,
        .filter-panel {
            box-shadow: none !important;
            background: white !important;
        }

        table {
            font-size: 12px;
        }

        .pagination {
            display: none !important;
        }
    }
</style>

<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Rapports des ventes
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="no-print rounded-3xl bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(79,70,229,0.22)] ring-1 ring-white/10">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-100/90">Performance</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">Rapports des ventes</h2>
                    </div>
                    <button type="button" class="print-button no-print" onclick="window.print()">
                        🖨️ Imprimer le rapport
                    </button>
                </div>
            </div>

            <form method="GET" class="filter-panel no-print rounded-2xl p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ $dateDebut }}"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white shadow-sm text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ $dateFin }}"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white shadow-sm text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400 mb-2">Module</label>
                    <select name="module" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white shadow-sm text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                        <option value="">Tous les modules</option>
                        <option value="secretariat" {{ $module === 'secretariat' ? 'selected' : '' }}>Secrétariat</option>
                        <option value="librairie" {{ $module === 'librairie' ? 'selected' : '' }}>Librairie</option>
                        <option value="boissons" {{ $module === 'boissons' ? 'selected' : '' }}>Boissons</option>
                        <option value="services" {{ $module === 'services' ? 'selected' : '' }}>Services</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400 mb-2">Caissière</label>
                    <select name="caissiere_id" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white shadow-sm text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                        <option value="">Toutes les caissières</option>
                        @foreach ($caissieres as $c)
                            <option value="{{ $c->id }}" {{ (string) $caissiereId === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 rounded-xl shadow-lg shadow-indigo-500/20 transition">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.rapports.index') }}" class="flex-1 text-center bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold py-2.5 rounded-xl transition hover:bg-slate-200 dark:hover:bg-slate-600">
                        Reset
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="stat-card p-5 border-l-4 border-indigo-500">
                    <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Total ventes</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ number_format($totalVentes, 0, ',', ' ') }} F</p>
                </div>
                <div class="stat-card p-5 border-l-4 border-emerald-500">
                    <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Nombre de ventes</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ $nombreVentes }}</p>
                </div>
                <div class="stat-card p-5 border-l-4 border-purple-500">
                    <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Bénéfice Unités</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ number_format($beneficeUnites, 0, ',', ' ') }} F</p>
                </div>
                <div class="stat-card p-5 border-l-4 border-amber-500">
                    <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Bénéfice WiFi</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ number_format($beneficeWifi, 0, ',', ' ') }} F</p>
                </div>
            </div>

            @if ($repartitionParModule->isNotEmpty())
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl p-4 sm:p-5 ring-1 ring-slate-200 dark:ring-slate-700">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200 mb-3 text-sm uppercase tracking-[0.18em]">Répartition par module</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($repartitionParModule as $r)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-700 px-4 py-2.5 text-sm ring-1 ring-slate-200 dark:ring-slate-600">
                                <span class="capitalize font-semibold text-slate-700 dark:text-slate-100">{{ $r->module }}</span>
                                <span class="text-slate-600 dark:text-slate-300">: {{ number_format($r->total, 0, ',', ' ') }} F ({{ $r->nb }} ventes)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-[0_18px_40px_rgba(15,23,42,0.06)] rounded-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left min-w-[760px]">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Module</th>
                                <th class="px-6 py-3">Caissière</th>
                                <th class="px-6 py-3">Produits</th>
                                <th class="px-6 py-3 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($ventes as $vente)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition">
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 capitalize text-slate-700 dark:text-slate-200 font-medium">{{ $vente->module }}</td>
                                    <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $vente->caissiere->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                        {{ $vente->lignes->map(fn($l) => ($l->produit->nom ?? '?') . ' x' . $l->quantite)->join(', ') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800 dark:text-white">{{ number_format($vente->montant_total, 0, ',', ' ') }} F</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        Aucune vente trouvée pour cette période/ces filtres.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="no-print">
                {{ $ventes->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
