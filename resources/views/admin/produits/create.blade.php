<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ➕ Nouveau produit - {{ ucfirst($module) }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm rounded-lg p-4">
                    <p class="font-semibold mb-1">Merci de corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($module === 'services')
                <div class="mb-4 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 text-sm rounded-lg p-4">
                    Ce produit sera enregistré comme <strong>service</strong> (pas de gestion de stock).
                </div>
            @endif

            <form action="{{ route('admin.produits.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-6">
                @csrf
                <input type="hidden" name="module" value="{{ $module }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du produit *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optionnel)</label>
                    <textarea name="description" rows="2"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Prix d'achat (F) @if ($module !== 'services') * @else (optionnel) @endif
                        </label>
                        <input type="number" step="0.01" name="prix_achat" value="{{ old('prix_achat', 0) }}" @if ($module !== 'services') required @endif
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix de vente détail (F) *</label>
                        <input type="number" step="0.01" name="prix_vente" value="{{ old('prix_vente') }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                @if ($module !== 'services')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantité en stock (détail) *</label>
                            <input type="number" name="quantite_stock" value="{{ old('quantite_stock', 0) }}" required
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seuil d'alerte stock *</label>
                            <input type="number" name="seuil_alerte_stock" value="{{ old('seuil_alerte_stock', 5) }}" required
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                @endif

                @if ($module !== 'services')
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Conditionnements optionnels</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Laisse vide si ce produit ne se vend qu'à l'unité (détail).</p>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Paquet : nombre d'unités détail</label>
                                <input type="number" name="paquet_quantite" value="{{ old('paquet_quantite') }}"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Paquet : prix de vente (F)</label>
                                <input type="number" step="0.01" name="paquet_prix" value="{{ old('paquet_prix') }}"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Carton : nombre d'unités détail</label>
                                <input type="number" name="carton_quantite" value="{{ old('carton_quantite') }}"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Carton : prix de vente (F)</label>
                                <input type="number" step="0.01" name="carton_prix" value="{{ old('carton_prix') }}"
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.produits.index', ['module' => $module]) }}"
                       class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">
                        <x-icons.plus />
                        Créer le produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>