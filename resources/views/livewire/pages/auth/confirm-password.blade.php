<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Ceci est une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.
    </div>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div x-data="{ showPassword: false }">
            <x-input-label for="password" value="Mot de passe" />

            <div class="relative">
                <x-text-input wire:model="password"
                              id="password"
                              class="block mt-1 w-full pr-10"
                              x-bind:type="showPassword ? 'text' : 'password'"
                              name="password"
                              required autocomplete="current-password" />

                <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-700 focus:outline-none"
                        aria-label="Afficher ou masquer le mot de passe">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12zm9.75 3.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5z" />
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.5A3.75 3.75 0 0 0 12 15.75A3.75 3.75 0 0 0 15.75 12M9.75 5.55A10.84 10.84 0 0 1 12 5.25c6 0 9.75 6.75 9.75 6.75a17.95 17.95 0 0 1-4.2 5.25M6.7 6.7A17.2 17.2 0 0 0 2.25 12S5.25 18.75 12 18.75a11.7 11.7 0 0 0 5.7-1.5" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                Confirmer
            </x-primary-button>
        </div>
    </form>
</div>