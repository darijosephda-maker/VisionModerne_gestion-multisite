<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'nom',
        'description',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'seuil_alerte_stock',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'prix_achat' => 'decimal:2',
            'prix_vente' => 'decimal:2',
            'actif' => 'boolean',
        ];
    }

    public function unites(): HasMany
    {
        return $this->hasMany(ProduitUnite::class);
    }

    public function venteLignes(): HasMany
    {
        return $this->hasMany(VenteLigne::class);
    }

    public function approvisionnements(): HasMany
    {
        return $this->hasMany(Approvisionnement::class);
    }

    public function stockBas(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte_stock;
    }
}