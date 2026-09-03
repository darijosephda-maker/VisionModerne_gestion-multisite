<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ➕ {{ __('Nouvel opérateur') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.stocks-unites.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom de l'opérateur</label>
                        <input type="text" name="operateur" value="{{ old('operateur') }}" required
                               placeholder="Ex : Orange, Moov Africa BF, Telecel"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        @error('operateur') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capital initial (F)</label>
                        <input type="number" name="capital_initial" value="{{ old('capital_initial') }}" min="0" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Le solde actuel démarrera à ce montant.</p>
                        @error('capital_initial') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Seuil d'alerte (F)</label>
                        <input type="number" name="seuil_alerte" value="{{ old('seuil_alerte') }}" min="0" required
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Le solde sera affiché en rouge sous ce montant.</p>
                        @error('seuil_alerte') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-md transition">
                            <x-icons.plus />
                            Créer l'opérateur
                        </button>
                        <a href="{{ route('admin.stocks-unites.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-admin-layout>
