<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            'module' => 'librairie',
            'nom' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'prix_achat' => 100,
            'prix_vente' => 250,
            'quantite_stock' => 10,
            'seuil_alerte_stock' => 2,
            'type' => 'stock',
            'actif' => true,
        ];
    }
}
