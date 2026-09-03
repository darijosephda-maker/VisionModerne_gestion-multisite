<?php

use App\Models\User;

test('seul l administrateur principal peut desactiver un compte', function () {
    $principal = User::factory()->create([
        'name' => 'Administrateur Principal',
        'email' => 'visionmoderneconstructionsarl@gmail.com',
        'role' => 'admin',
        'actif' => true,
    ]);
    $user = User::factory()->create(['role' => 'caissiere', 'actif' => true]);

    $this->actingAs($principal)
        ->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'actif' => '0',
        ])
        ->assertRedirect(route('admin.users.index'));

    expect($user->refresh()->actif)->toBeFalse();
});

test('un administrateur secondaire ne peut pas desactiver un compte', function () {
    $admin = User::factory()->create([
        'email' => 'autre-admin@example.com',
        'role' => 'admin',
        'actif' => true,
    ]);
    $user = User::factory()->create(['role' => 'caissiere', 'actif' => true]);

    $this->actingAs($admin)
        ->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'actif' => '0',
        ])
        ->assertRedirect(route('admin.users.index'));

    expect($user->refresh()->actif)->toBeTrue();
});