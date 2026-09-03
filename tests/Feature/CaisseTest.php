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
