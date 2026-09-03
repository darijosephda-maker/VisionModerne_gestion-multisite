<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ __('Informations personnelles') }}
                </h2>
                <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                    {{ __('Gardez vos coordonnées à jour afin que votre compte reste facilement identifiable.') }}
                </p>
            </div>
        </div>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-8 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" required autofocus autocomplete="name" />
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">{{ __('Ce nom sera affiché dans toute l’application.') }}</p>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Adresse e-mail professionnelle')" class="font-semibold text-slate-700 dark:text-slate-300" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Votre adresse e-mail n’est pas vérifiée.') }}

                        <button wire:click.prevent="sendVerification" class="font-semibold text-indigo-600 underline decoration-indigo-300 underline-offset-2 hover:text-indigo-500 dark:text-indigo-400">
                            {{ __('Cliquez ici pour renvoyer l’e-mail de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4 border-t border-slate-100 pt-5 dark:border-slate-800">
            <x-primary-button class="bg-indigo-600 normal-case tracking-normal hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700">{{ __('Enregistrer les modifications') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Modifications enregistrées.') }}
            </x-action-message>
        </div>
    </form>
</section>
