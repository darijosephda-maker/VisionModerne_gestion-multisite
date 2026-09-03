<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✍️ {{ __('Modifier le forfait') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.wifi-forfaits.update', $wifiForfait) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du forfait</label>
                        <input type="text" name="nom_forfait" value="{{ old('nom_forfait', $wifiForfait->nom_forfait) }}" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        @error('nom_forfait') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix de revient (F)</label>
                        <input type="number" name="prix_cout" value="{{ old('prix_cout', $wifiForfait->prix_cout) }}" min="0" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        @error('prix_cout') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prix de vente (F)</label>
                        <input type="number" name="prix_vente" value="{{ old('prix_vente', $wifiForfait->prix_vente) }}" min="0" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Doit être supérieur ou égal au prix de revient.</p>
                        @error('prix_vente') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-md transition">
                            <x-icons.check />
                            Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.wifi-forfaits.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-admin-layout>
