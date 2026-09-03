<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✍️ Modifier le produit - {{ $produit->nom }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm rounded-lg p-4">
                    <p class="font-semibold mb-1">Merci de corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.produits.update', $produit) }}" method="POST" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du produit *</label>
                    <input type="text" name="nom" value="{{ old('nom', $produit->nom) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $produit->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix d'achat (F) *</label>
                        <input type="number" step="0.01" name="prix_achat" value="{{ old('prix_achat', $produit->prix_achat) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix de vente détail (F) *</label>
                        <input type="number" step="0.01" name="prix_vente" value="{{ old('prix_vente', $produit->prix_vente) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantité en stock (détail) *</label>
                        <input type="number" name="quantite_stock" value="{{ old('quantite_stock', $produit->quantite_stock) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seuil d'alerte stock *</label>
                        <input type="number" name="seuil_alerte_stock" value="{{ old('seuil_alerte_stock', $produit->seuil_alerte_stock) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="actif" value="0">
                    <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $produit->actif) ? 'checked' : '' }}
                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                    <label for="actif" class="text-sm text-gray-700 dark:text-gray-300">Produit actif (visible à la vente)</label>
                </div>

                @if ($produit->unites->isNotEmpty())
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Conditionnements existants</p>
                        <div class="space-y-2">
                            @foreach ($produit->unites as $unite)
                                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-md px-4 py-2 text-sm">
                                    <span class="text-gray-700 dark:text-gray-300 capitalize">{{ $unite->type_unite }} ({{ $unite->quantite_equivalente_detail }} détail{{ $unite->quantite_equivalente_detail > 1 ? 's' : '' }})</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">{{ number_format($unite->prix_vente_unite, 0, ',', ' ') }} F</span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">La gestion détaillée des conditionnements (ajout/suppression) sera disponible dans une prochaine étape.</p>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.produits.index', ['module' => $produit->module]) }}"
                        class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">
                        <x-icons.check />
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
