<x-admin-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(37,99,235,0.22)] ring-1 ring-white/10">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-100/90">Gestion des stocks</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">📦 Produits - {{ ucfirst($module) }}</h2>
                    </div>
                    <a href="{{ route('admin.produits.create', ['module' => $module]) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl ring-1 ring-white/30 transition">
                        <x-icons.plus />
                        Ajouter produit
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 rounded-2xl bg-white dark:bg-slate-800 p-3 ring-1 ring-slate-200 dark:ring-slate-700">
                @foreach (['secretariat' => 'Secrétariat', 'librairie' => 'Librairie', 'boissons' => 'Boissons', 'services' => 'Services'] as $key => $label)
                    <a href="{{ route('admin.produits.index', ['module' => $key]) }}"
                       class="px-3.5 py-2 text-xs sm:text-sm font-semibold rounded-lg transition
                       {{ $module === $key ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.produits.index') }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl p-4 ring-1 ring-slate-200 dark:ring-slate-700">
                <input type="hidden" name="module" value="{{ $module }}">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                    <div class="flex-1 flex items-center gap-2">
                        <x-icons.search class="text-slate-400 shrink-0" />
                        <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher par nom ou description..."
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 outline-none">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition">
                            Chercher
                        </button>
                        @if ($search)
                            <a href="{{ route('admin.produits.index', ['module' => $module]) }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition hover:bg-slate-200 dark:hover:bg-slate-600">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm rounded-lg p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm rounded-lg p-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-[0_18px_40px_rgba(15,23,42,0.06)] rounded-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Nom</th>
                                <th class="px-6 py-3">Prix vente</th>
                                <th class="px-6 py-3">Stock</th>
                                <th class="px-6 py-3">Unités</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($produits as $produit)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $produit->nom }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ number_format($produit->prix_vente, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4">
                                        <span class="{{ $produit->stockBas() ? 'text-red-600 dark:text-red-400 font-bold' : 'text-slate-600 dark:text-slate-300' }}">
                                            {{ $produit->quantite_stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                        {{ $produit->unites->pluck('type_unite')->map(fn($u) => ucfirst($u))->join(', ') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($produit->actif)
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Actif</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Inactif</span>
                                        @endif
                                    </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.produits.edit', $produit) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded transition"
                                           title="Modifier">
                                            <x-icons.edit />
                                            <span class="text-sm">Modifier</span>
                                        </a>
                                        <form action="{{ route('admin.produits.destroy', $produit) }}" method="POST" class="inline"
                                            onsubmit="return confirm('⚠️ Action irréversible.\n\nSupprimer définitivement « {{ $produit->nom }} » ?\nLe stock actuel ({{ $produit->quantite_stock }} unités) sera perdu.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition"
                                                    title="Supprimer">
                                                <x-icons.trash />
                                                <span class="text-sm">Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    @if ($search)
                                        Aucun produit ne correspond à « {{ $search }} » dans ce module.
                                    @else
                                        Aucun produit dans ce module pour le moment.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $produits->links() }}

        </div>
    </div>
</x-admin-layout>