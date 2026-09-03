<x-admin-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl bg-gradient-to-r from-orange-700 via-orange-600 to-amber-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(194,65,12,0.22)] ring-1 ring-white/10">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-orange-100/90">Partenaires</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">🏢 Fournisseurs</h2>
                    </div>
                    <a href="{{ route('admin.fournisseurs.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl ring-1 ring-white/30 transition">
                        <x-icons.plus />
                        Ajouter fournisseur
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.fournisseurs.index') }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl p-4 ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
                    <div class="flex items-center gap-2">
                        <x-icons.search class="text-slate-400 shrink-0" />
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nom, téléphone, localité..."
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-900 outline-none">
                    </div>
                    <select name="module" onchange="this.form.submit()"
                            class="px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-900 outline-none">
                        <option value="">Tous les modules</option>
                        @foreach (['secretariat' => 'Secrétariat', 'librairie' => 'Librairie', 'boissons' => 'Boissons', 'services' => 'Services', 'general' => 'Général'] as $key => $label)
                            <option value="{{ $key }}" {{ $module === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="statut" onchange="this.form.submit()"
                            class="px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-900 outline-none">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ $statut === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $statut === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-orange-500/20 transition">
                            Filtrer
                        </button>
                        @if ($search || $module || $statut)
                            <a href="{{ route('admin.fournisseurs.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition hover:bg-slate-200 dark:hover:bg-slate-600">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

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

            <div class="bg-white dark:bg-slate-800 shadow-[0_18px_40px_rgba(15,23,42,0.06)] rounded-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Nom</th>
                                <th class="px-6 py-3">Téléphone</th>
                                <th class="px-6 py-3">Localité</th>
                                <th class="px-6 py-3">Module</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($fournisseurs as $fournisseur)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $fournisseur->nom }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $fournisseur->telephone }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $fournisseur->localite ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-xs font-medium">{{ ucfirst($fournisseur->module) }}</td>
                                    <td class="px-6 py-4">
                                        @if ($fournisseur->actif)
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Actif</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('admin.fournisseurs.edit', $fournisseur) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition text-sm font-medium">
                                                <x-icons.edit />
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.fournisseurs.destroy', $fournisseur) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('⚠️ Supprimer définitivement « {{ $fournisseur->nom }} » ?');">
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
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        @if ($search || $module || $statut)
                                            Aucun fournisseur ne correspond à ces critères.
                                        @else
                                            Aucun fournisseur enregistré.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $fournisseurs->links() }}
            </div>

        </div>
    </div>
</x-admin-layout>