<x-admin-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl bg-gradient-to-r from-cyan-700 via-cyan-600 to-blue-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(34,150,243,0.22)] ring-1 ring-white/10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/90">Opérateurs télécom</p>
                    <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">📱 Unités — Recharges</h2>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm rounded-2xl p-4 ring-1 ring-emerald-200 dark:ring-emerald-800/50">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm rounded-2xl p-4 ring-1 ring-red-200 dark:ring-red-800/50">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-wrap gap-2 rounded-2xl bg-white dark:bg-slate-800 p-3 ring-1 ring-slate-200 dark:ring-slate-700">
                <span class="px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300">
                    Opérateurs
                </span>
                <a href="{{ route('admin.wifi-forfaits.index') }}"
                   class="px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                    Forfaits WiFi
                </a>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.stocks-unites.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-cyan-500/20 transition">
                    <x-icons.plus />
                    Nouvel opérateur
                </a>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow-[0_18px_40px_rgba(15,23,42,0.06)] rounded-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Opérateur</th>
                                <th class="px-6 py-3">Solde</th>
                                <th class="px-6 py-3">Capital initial</th>
                                <th class="px-6 py-3">Seuil d'alerte</th>
                                <th class="px-6 py-3">Dernière recharge</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($stocksUnites as $stock)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $stock->operateur }}</td>
                                    <td class="px-6 py-4 {{ $stock->solde_actuel <= $stock->seuil_alerte ? 'text-red-600 dark:text-red-400 font-bold' : 'text-slate-800 dark:text-white' }}">
                                        {{ number_format($stock->solde_actuel, 0, ',', ' ') }} F
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($stock->capital_initial, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($stock->seuil_alerte, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-xs">
                                        {{ $stock->date_alimentation ? $stock->date_alimentation->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button"
                                                onclick="document.getElementById('modal-recharge-{{ $stock->id }}').classList.remove('hidden')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition text-sm font-medium">
                                            <x-icons.download />
                                            Recharger
                                        </button>
                                    </td>
                                    <!-- Modal recharge -->
                                    <div id="modal-recharge-{{ $stock->id }}" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
                                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6 w-full max-w-md">
                                            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Recharger {{ $stock->operateur }}</h3>
                                            <form action="{{ route('admin.stocks-unites.recharger', $stock) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:text-slate-300 mb-2">Montant à ajouter (F)</label>
                                                    <input type="number" name="montant" min="1" required
                                                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900 outline-none">
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl shadow-lg shadow-emerald-500/20 transition">
                                                        Confirmer
                                                    </button>
                                                    <button type="button"
                                                            onclick="document.getElementById('modal-recharge-{{ $stock->id }}').classList.add('hidden')"
                                                            class="flex-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold px-4 py-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                                                        Annuler
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                    Aucun opérateur enregistré pour le moment.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>