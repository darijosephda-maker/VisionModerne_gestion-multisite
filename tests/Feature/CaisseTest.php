<?php

use App\Models\Produit;
use App\Models\ProduitUnite;
use App\Models\User;
use App\Models\Vente;

it('enregistre une vente avec infos client et redirige vers la facture', function () {
    $user = User::factory()->create([
        'role' => 'caissiere',
    ]);

    $produit = Produit::factory()->create([
        'module' => 'librairie',
        'nom' => 'Stylo bleu',
        'quantite_stock' => 20,
        'actif' => true,
    ]);

    $unite = ProduitUnite::factory()->create([
        'produit_id' => $produit->id,
        'type_unite' => 'detail',
        'quantite_equivalente_detail' => 1,
        'prix_vente_unite' => 250,
        'actif' => true,
    ]);

    $response = $this->actingAs($user)->post(route('caisse.store'), [
        'module' => 'librairie',
        'client_nom' => 'Nikiema',
        'client_prenom' => 'Awa',
        'client_telephone' => '76000000',
        'lignes' => [[
            'produit_id' => $produit->id,
            'produit_unite_id' => $unite->id,
            'quantite' => 2,
        ]],
    ]);

    $response->assertRedirect();
    $vente = Vente::first();
    expect($vente)->not->toBeNull()
        ->and($vente->client_nom)->toBe('Nikiema')
        ->and($vente->client_prenom)->toBe('Awa')
        ->and($vente->client_telephone)->toBe('76000000')
        ->and((string) $vente->montant_total)->toBe('500.00');
});

it('enregistre la vingtieme vente sans limite artificielle', function () {
    $user = User::factory()->create([
        'role' => 'caissiere',
    ]);

    $produit = Produit::factory()->create([
        'module' => 'librairie',
        'quantite_stock' => 20,
        'actif' => true,
    ]);

    $unite = ProduitUnite::factory()->create([
        'produit_id' => $produit->id,
        'type_unite' => 'detail',
        'quantite_equivalente_detail' => 1,
        'prix_vente_unite' => 250,
        'actif' => true,
    ]);

    foreach (range(1, 20) as $numeroVente) {
        $response = $this->actingAs($user)->post(route('caisse.store'), [
            'module' => 'librairie',
            'lignes' => [[
                'produit_id' => $produit->id,
                'produit_unite_id' => $unite->id,
                'quantite' => 1,
            ]],
        ]);

        $response->assertRedirect(route('caisse.facture', ['vente' => $numeroVente]));
    }

    expect(Vente::count())->toBe(20)
        ->and($produit->fresh()->quantite_stock)->toBe(0);
});

it('enregistre une facture avec un produit et un service de modules differents', function () {
    $user = User::factory()->create([
        'role' => 'caissiere',
    ]);

    $produit = Produit::factory()->create([
        'module' => 'librairie',
        'quantite_stock' => 5,
        'actif' => true,
    ]);

    $unite = ProduitUnite::factory()->create([
        'produit_id' => $produit->id,
        'type_unite' => 'detail',
        'quantite_equivalente_detail' => 1,
        'prix_vente_unite' => 250,
        'actif' => true,
    ]);

    $response = $this->actingAs($user)->post(route('caisse.store'), [
        'module' => 'mixte',
        'lignes' => [
            [
                'module' => 'librairie',
                'produit_id' => $produit->id,
                'produit_unite_id' => $unite->id,
                'quantite' => 1,
            ],
            [
                'module' => 'services',
                'description_libre' => 'Photocopie',
                'prix' => 100,
                'quantite' => 2,
            ],
        ],
    ]);

    $response->assertRedirect();
    $vente = Vente::latest('id')->first();

    expect($vente->module)->toBe('mixte')
        ->and($vente->lignes)->toHaveCount(2)
        ->and($vente->lignes->pluck('module')->sort()->values()->all())->toBe(['librairie', 'services'])
        ->and((string) $vente->montant_total)->toBe('450.00')
        ->and($produit->fresh()->quantite_stock)->toBe(4);
});

it('affiche les factures mixtes dans la liste admin', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $vente = Vente::create([
        'caissiere_id' => $admin->id,
        'module' => 'mixte',
        'montant_total' => 350,
        'statut' => 'validee',
        'date_vente' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.factures.index'))
        ->assertOk()
        ->assertSee('#' . str_pad($vente->id, 6, '0', STR_PAD_LEFT))
        ->assertSee('mixte');

    $this->actingAs($admin)
        ->get(route('admin.factures.index', ['module' => 'mixte']))
        ->assertOk()
        ->assertSee('#' . str_pad($vente->id, 6, '0', STR_PAD_LEFT));
});
