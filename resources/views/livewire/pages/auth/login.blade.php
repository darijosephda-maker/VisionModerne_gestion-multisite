<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div class="space-y-2.5">
    <div class="text-center">
        <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100">Connexion</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Accédez à votre espace de gestion</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form wire:submit.prevent="login" novalidate class="space-y-3">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse e-mail" class="text-sm font-semibold text-slate-700 dark:text-slate-200" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full h-11 border-slate-300 bg-slate-50/80 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100" type="email" name="email" autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Mot de passe" class="text-sm font-semibold text-slate-700 dark:text-slate-200" />

            <div class="relative mt-1">
                <input wire:model="form.password" id="password" type="password"
                       class="block w-full h-11 border border-slate-300 bg-slate-50/80 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100 pr-11"
                       name="password"
                       autocomplete="current-password" />
                <button type="button" onclick="toggleLoginPassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition" aria-label="Afficher ou masquer le mot de passe">
                    <svg class="h-5 w-5 icon-eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="h-5 w-5 icon-eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="block pt-0.5">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-300">Se souvenir de moi</span>
            </label>
        </div>

        <div class="flex items-center justify-between pt-1 gap-3">
            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm text-slate-600 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400 underline-offset-2 hover:underline transition" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @else
                <span></span>
            @endif

            <x-primary-button class="text-sm font-semibold px-4 py-3 rounded-xl shadow-md shadow-indigo-600/20 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600">
                Se connecter
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    function toggleLoginPassword(button) {
        const wrapper = button.closest('.relative');
        const input = wrapper.querySelector('input');
        const eyeOpen = button.querySelector('.icon-eye-open');
        const eyeClosed = button.querySelector('.icon-eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>