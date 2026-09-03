<?php

use App\Models\User;

test('admin site content page can be rendered', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('admin.site-contenu.index'));

    $response->assertOk();
    $response->assertSee('Contenu du site');
});
