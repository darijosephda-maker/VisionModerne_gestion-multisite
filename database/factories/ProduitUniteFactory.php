<?php

namespace Database\Factories;

use App\Models\ProduitUnite;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitUniteFactory extends Factory
{
    protected $model = ProduitUnite::class;

    public function definition(): array
    {
        return [
            'produit_id' => 1,
            'type_unite' => 'detail',
            'quantite_equivalente_detail' => 1,
            'prix_vente_unite' => 250,
            'actif' => true,
        ];
    }
}
