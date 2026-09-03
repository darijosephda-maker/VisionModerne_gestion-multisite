<x-admin-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">Gestion financière</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Registre des factures</h1>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8 dark:bg-slate-950 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-900 to-sky-800 p-6 shadow-xl sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl text-white">
                        <p class="text-sm font-semibold text-sky-200">Suivi centralisé</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight">Toutes les factures, au même endroit.</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-200">Retrouvez rapidement une facture par période, client, caissière ou module, puis ouvrez sa version imprimable.</p>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <div class="rounded-xl bg-white/10 px-4 py-3 text-white ring-1 ring-white/15">
                            <p class="text-xs text-sky-200">Factures trouvées</p>
                            <p class="mt-1 text-xl font-bold">{{ number_format($totalFactures, 0, ',', ' ') }}</p>
                        </div>
                        <div class="rounded-xl bg-white/10 px-4 py-3 text-white ring-1 ring-white/15">
                            <p class="text-xs text-sky-200">Montant total</p>
                            <p class="mt-1 text-xl font-bold">{{ number_format($montantTotal, 0, ',', ' ') }} F</p>
                        </div>
                    </div>
                </div>
            </section>

            <form method="GET" action="{{ route('admin.factures.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Rechercher une facture</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Définissez une période ou combinez plusieurs critères.</p>
                    </div>
                    <a href="{{ route('admin.factures.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Réinitialiser la recherche</a>
                </div>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div class="xl:col-span-2">
                        <label for="recherche" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Recherche libre</label>
                        <input id="recherche" type="search" name="recherche" value="{{ $recherche }}" placeholder="N° facture, client, téléphone..." class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </div>
                    <div>
                        <label for="date_debut" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date de début</label>
                        <input id="date_debut" type="date" name="date_debut" value="{{ $dateDebut }}" class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </div>
                    <div>
                        <label for="date_fin" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date de fin</label>
                        <input id="date_fin" type="date" name="date_fin" value="{{ $dateFin }}" class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </div>
                    <div>
                        <label for="module" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Module</label>
                        <select id="module" name="module" class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Tous les modules</option>
                            <option value="secretariat" @selected($module === 'secretariat')>Secrétariat</option>
                            <option value="librairie" @selected($module === 'librairie')>Librairie</option>
                            <option value="boissons" @selected($module === 'boissons')>Boissons</option>
                            <option value="services" @selected($module === 'services')>Services</option>
                            <option value="unites_wifi" @selected($module === 'unites_wifi')>Unités & WiFi</option>
                        </select>
                    </div>
                    <div>
                        <label for="caissiere_id" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Caissière</label>
                        <select id="caissiere_id" name="caissiere_id" class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Toutes les caissières</option>
                            @foreach ($caissieres as $caissiere)
                                <option value="{{ $caissiere->id }}" @selected((string) $caissiereId === (string) $caissiere->id)>{{ $caissiere->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-5 flex justify-end border-t border-slate-100 pt-5 dark:border-slate-800">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path stroke-linecap="round" d="m20 20-4-4"></path></svg>
                        Rechercher les factures
                    </button>
                </div>
            </form>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-slate-800">
                    <div>
                        <h2 class="font-bold text-slate-900 dark:text-white">Résultats</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">Ventes validées uniquement</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800/70 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4">Facture</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Caissière</th>
                                <th class="px-6 py-4">Module</th>
                                <th class="px-6 py-4 text-right">Montant</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($factures as $facture)
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4 font-bold text-indigo-700 dark:text-indigo-400">#{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-600 dark:text-slate-300">{{ $facture->date_vente->format('d/m/Y à H:i') }}</td>
                                    <td class="px-6 py-4 text-slate-700 dark:text-slate-200">
                                        {{ trim($facture->client_prenom . ' ' . $facture->client_nom) ?: 'Client comptoir' }}
                                        @if ($facture->client_telephone)
                                            <span class="mt-1 block text-xs text-slate-400">{{ $facture->client_telephone }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 dark:text-slate-200">{{ $facture->caissiere->name ?? 'Compte supprimé' }}</td>
                                    <td class="px-6 py-4"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ str_replace('_', ' ', $facture->module) }}</span></td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900 dark:text-white">{{ number_format($facture->montant_total, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4 text-right"><a href="{{ route('caisse.facture', $facture) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-bold text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-500/10" title="Ouvrir la facture imprimable"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z" /></svg>Voir</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-16 text-center"><div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-xl dark:bg-slate-800">🧾</div><p class="mt-4 font-semibold text-slate-700 dark:text-slate-200">Aucune facture trouvée</p><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Essayez d’élargir la période ou de modifier vos critères.</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($factures->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4 dark:border-slate-800">{{ $factures->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-admin-layout>
