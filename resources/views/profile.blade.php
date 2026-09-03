@php
    $user = auth()->user();
    $layout = $user->isAdmin() ? 'admin-layout' : 'caisse-layout';
    $roleLabel = $user->isAdmin() ? 'Administrateur' : 'Caissière';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400">{{ __('Paramètres du compte') }}</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ __('Mon profil') }}</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Gérez vos informations personnelles et la sécurité de votre compte.') }}</p>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-9rem)] bg-slate-50 py-8 dark:bg-slate-950 sm:py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-sky-600 px-6 py-7 sm:px-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-2xl font-bold text-white ring-1 ring-white/30">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 text-white">
                                <p class="text-sm font-medium text-indigo-100">{{ __('Bienvenue') }}</p>
                                <h1 class="truncate text-2xl font-bold">{{ $user->name }}</h1>
                                <p class="truncate text-sm text-indigo-100">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <span class="rounded-full bg-white/15 px-3 py-1.5 text-white ring-1 ring-white/25">{{ $roleLabel }}</span>
                            <span class="rounded-full bg-emerald-400/20 px-3 py-1.5 text-emerald-50 ring-1 ring-emerald-200/30">
                                <span class="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-emerald-300 align-middle"></span>{{ __('Compte actif') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 border-t border-slate-100 px-6 py-5 sm:grid-cols-3 sm:px-8 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('Niveau d’accès') }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $roleLabel }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('État de l’e-mail') }}</p>
                        <p class="mt-1 text-sm font-semibold {{ $user->hasVerifiedEmail() ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $user->hasVerifiedEmail() ? __('Vérifié') : __('Vérification en attente') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('Membre depuis') }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $user->created_at?->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.5fr)_minmax(18rem,0.8fr)]">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                        <div class="max-w-2xl">
                    <livewire:profile.update-profile-information-form />
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                        <div class="max-w-2xl">
                    <livewire:profile.update-password-form />
                        </div>
                    </div>
                </div>

                <aside class="h-fit rounded-2xl border border-rose-200 bg-rose-50/60 p-6 shadow-sm dark:border-rose-900/70 dark:bg-rose-950/20 sm:p-8">
                    <livewire:profile.delete-user-form />
                </aside>
            </div>
        </div>
    </div>
</x-dynamic-component>
