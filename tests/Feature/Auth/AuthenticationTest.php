<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.login');
});

test('no validation errors on page load', function () {
    $component = Volt::test('pages.auth.login');

    $component->assertHasNoErrors();
});

test('login form uses a single password field to avoid Livewire binding issues', function () {
    $component = Volt::test('pages.auth.login');

    $component->assertDontSee('password-visible');
    $component->assertDontSee('name="password-visible"');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('invalid password error is displayed in french', function () {
    app()->setLocale('fr');

    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component->assertHasErrors([
        'form.email' => 'Email ou mot de passe incorrect.',
    ]);
});

test('required field error is displayed in french when form is submitted empty', function () {
    app()->setLocale('fr');

    $component = Volt::test('pages.auth.login');

    $component->call('login');

    $component->assertHasErrors([
        'form.email' => 'Ce champ est obligatoire.',
        'form.password' => 'Ce champ est obligatoire.',
    ]);
});

test('cashiers are redirected from the dashboard to the cash register', function () {
    $user = User::factory()->create(['role' => 'caissiere']);

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertRedirect(route('caisse.index', absolute: false));
});

test('database seed creates the default admin user', function () {
    $this->seed();

    $this->assertDatabaseHas('users', [
        'email' => 'visionmoderneconstructionsarl@gmail.com',
        'role' => 'admin',
    ]);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.navigation');

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});
