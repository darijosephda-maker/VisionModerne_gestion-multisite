<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✏️ Modifier — {{ $user->name }}
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

            @if ($user->id === auth()->id())
                <div class="mb-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-sm rounded-lg p-4">
                    C'est votre propre compte : le rôle et le statut restent verrouillés sur Admin / Actif pour éviter de vous bloquer l'accès.
                </div>
            @endif

            <form x-data="{ confirming: false }" x-ref="profileForm" @submit.prevent="confirming = true" action="{{ route('admin.users.update', $user) }}" method="POST" class="relative bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom complet *</label>
                    <input x-ref="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                    <input x-ref="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div x-data="{ showPassword: false }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nouveau mot de passe</label>
                        <div class="relative">
                            <input x-bind:type="showPassword ? 'text' : 'password'" name="password"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                    aria-label="Afficher ou masquer le mot de passe">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12zm9.75 3.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5z" />
                                </svg>
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.5A3.75 3.75 0 0 0 12 15.75A3.75 3.75 0 0 0 15.75 12M9.75 5.55A10.84 10.84 0 0 1 12 5.25c6 0 9.75 6.75 9.75 6.75a17.95 17.95 0 0 1-4.2 5.25M6.7 6.7A17.2 17.2 0 0 0 2.25 12S5.25 18.75 12 18.75a11.7 11.7 0 0 0 5.7-1.5" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Laisser vide pour ne pas changer.</p>
                    </div>
                    <div x-data="{ showPasswordConfirmation: false }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmer le mot de passe</label>
                        <div class="relative">
                            <input x-bind:type="showPasswordConfirmation ? 'text' : 'password'" name="password_confirmation"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                            <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                    aria-label="Afficher ou masquer la confirmation du mot de passe">
                                <svg x-show="!showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12zm9.75 3.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5z" />
                                </svg>
                                <svg x-show="showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.5A3.75 3.75 0 0 0 12 15.75A3.75 3.75 0 0 0 15.75 12M9.75 5.55A10.84 10.84 0 0 1 12 5.25c6 0 9.75 6.75 9.75 6.75a17.95 17.95 0 0 1-4.2 5.25M6.7 6.7A17.2 17.2 0 0 0 2.25 12S5.25 18.75 12 18.75a11.7 11.7 0 0 0 5.7-1.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rôle *</label>
                    <select name="role" required {{ $user->id === auth()->id() ? 'disabled' : '' }}
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-60">
                        <option value="caissiere" {{ old('role', $user->role) === 'caissiere' ? 'selected' : '' }}>Caissière</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if ($user->id === auth()->id())
                        <input type="hidden" name="role" value="admin">
                    @endif
                </div>

                @if (auth()->user()->isPrincipalAdmin())
                <div class="flex items-center gap-2">
                    @if ($user->id === auth()->id())
                        <input type="hidden" name="actif" value="1">
                    @else
                        <input type="hidden" name="actif" value="0">
                    @endif
                    <input type="checkbox" name="actif" id="actif" value="1"
                              x-ref="actif"
                           {{ old('actif', $user->actif) ? 'checked' : '' }}
                           {{ $user->id === auth()->id() ? 'disabled' : '' }}
                           class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 disabled:opacity-60">
                    <label for="actif" class="text-sm font-medium text-gray-700 dark:text-gray-300">Compte actif</label>
                </div>
                @else
                    <input type="hidden" name="actif" value="{{ $user->actif ? '1' : '0' }}">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-700/40 dark:text-slate-300">
                        Seul l'Administrateur Principal peut activer ou désactiver un compte.
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">
                        <x-icons.check />
                        Enregistrer les modifications
                    </button>
                </div>

                <div x-cloak x-show="confirming" x-transition.opacity class="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-slate-950/40 p-4">
                    <div @click.outside="confirming = false" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-800">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Confirmer la mise à jour</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Vérifiez les informations avant leur enregistrement.</p>
                        <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="grid grid-cols-2 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:bg-slate-700/60 dark:text-slate-300">
                                <div class="px-4 py-3">Anciennes informations</div>
                                <div class="border-l border-slate-200 px-4 py-3 dark:border-slate-700">Nouvelles informations</div>
                            </div>
                            <div class="divide-y divide-slate-200 text-sm dark:divide-slate-700">
                                <div class="grid grid-cols-2">
                                    <div class="px-4 py-3 text-slate-600 dark:text-slate-300">Nom : {{ $user->name }}</div>
                                    <div class="border-l border-slate-200 px-4 py-3 font-semibold text-slate-900 dark:border-slate-700 dark:text-white" x-text="'Nom : ' + $refs.name.value"></div>
                                </div>
                                <div class="grid grid-cols-2">
                                    <div class="px-4 py-3 text-slate-600 dark:text-slate-300">E-mail : {{ $user->email }}</div>
                                    <div class="border-l border-slate-200 px-4 py-3 font-semibold text-slate-900 dark:border-slate-700 dark:text-white" x-text="'E-mail : ' + $refs.email.value"></div>
                                </div>
                                <div class="grid grid-cols-2">
                                    <div class="px-4 py-3 text-slate-600 dark:text-slate-300">Statut : {{ $user->actif ? 'Actif' : 'Inactif' }}</div>
                                    <div class="border-l border-slate-200 px-4 py-3 font-semibold text-slate-900 dark:border-slate-700 dark:text-white" x-text="'Statut : ' + ($refs.actif?.checked ? 'Actif' : 'Inactif')"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="confirming = false" class="rounded-md px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">Annuler</button>
                            <button type="button" @click="$refs.profileForm.submit()" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Confirmer et enregistrer</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>