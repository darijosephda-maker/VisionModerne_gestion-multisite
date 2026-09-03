<x-admin-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(16,185,129,0.22)] ring-1 ring-white/10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-teal-100/90">Forfaits Internet</p>
                    <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">📶 Forfaits WiFi</h2>
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
                <a href="{{ route('admin.stocks-unites.index') }}"
                   class="px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                    Opérateurs
                </a>
                <span class="px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300">
                    Forfaits WiFi
                </span>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.wifi-forfaits.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-teal-500/20 transition">
                    <x-icons.plus />
                    Nouveau forfait
                </a>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow-[0_18px_40px_rgba(15,23,42,0.06)] rounded-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Forfait</th>
                                <th class="px-6 py-3">Prix revient</th>
                                <th class="px-6 py-3">Prix vente</th>
                                <th class="px-6 py-3">Marge</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($wifiForfaits as $forfait)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition {{ ! $forfait->actif ? 'opacity-60' : '' }}">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $forfait->nom_forfait }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($forfait->prix_cout, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">{{ number_format($forfait->prix_vente, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4 font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($forfait->marge(), 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $forfait->actif ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' }}">
                                            {{ $forfait->actif ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('admin.wifi-forfaits.edit', $forfait) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-lg transition text-sm font-medium">
                                                <x-icons.edit />
                                                Modifier
                                            </a>
                                            <form method="POST" action="{{ route('admin.wifi-forfaits.toggle-actif', $forfait) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition text-sm font-medium">
                                                    <x-icons.check />
                                                    {{ $forfait->actif ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.wifi-forfaits.destroy', $forfait) }}" class="inline" onsubmit="return confirm('Supprimer ce forfait ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition text-sm font-medium">
                                                    <x-icons.trash />
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Aucun forfait WiFi enregistré pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-admin-layout>