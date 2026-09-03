<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Sécurité du compte') }}</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ __('Utilisez un mot de passe robuste et mettez-le régulièrement à jour pour protéger votre accès.') }}</p>
            </div>
        </div>
    </header>

    <form wire:submit="updatePassword" class="mt-8 space-y-6">
        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" class="font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" class="font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="password" id="update_password_password" name="password" type="password" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le nouveau mot de passe')" class="font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-wrap items-center gap-4 border-t border-slate-100 pt-5 dark:border-slate-800">
            <x-primary-button class="bg-sky-600 normal-case tracking-normal hover:bg-sky-500 focus:bg-sky-500 active:bg-sky-700">{{ __('Mettre à jour le mot de passe') }}</x-primary-button>

            <x-action-message class="me-3" on="password-updated">
                {{ __('Mot de passe mis à jour.') }}
            </x-action-message>
        </div>
    </form>
</section>
