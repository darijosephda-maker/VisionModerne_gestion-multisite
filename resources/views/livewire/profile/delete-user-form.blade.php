<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        if (! Auth::user()->isPrincipalAdmin()) {
            $this->addError('password', 'Seul l’Administrateur Principal peut supprimer son compte.');

            return;
        }

        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/');
    }
}; ?>

<section class="space-y-6">
    @if (! auth()->user()->isPrincipalAdmin())
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 text-lg" aria-hidden="true">🔒</span>
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Suppression protégée</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">La suppression définitive de votre compte est réservée à l’Administrateur Principal.</p>
                </div>
            </div>
        </div>
    @else
    <header>
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-10.68 0c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0C8.91 1.968 8 2.952 8 4.132v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-rose-900 dark:text-rose-100">{{ __('Supprimer le compte') }}</h2>
                <p class="mt-1 text-sm leading-6 text-rose-800/75 dark:text-rose-200/70">{{ __('Cette action est définitive et irréversible.') }}</p>
            </div>
        </div>

        <p class="mt-5 text-sm leading-6 text-rose-900/75 dark:text-rose-100/70">{{ __('Toutes les données associées seront définitivement supprimées. Vérifiez que vous n’avez plus besoin de ce compte avant de continuer.') }}</p>
    </header>

    <x-danger-button
        class="normal-case tracking-normal"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Supprimer le compte') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Voulez-vous vraiment supprimer votre compte ?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Après suppression, toutes vos ressources et données seront définitivement supprimées. Saisissez votre mot de passe pour confirmer cette action.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Mot de passe') }}" class="sr-only" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Mot de passe') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Supprimer le compte') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    @endif
</section>
