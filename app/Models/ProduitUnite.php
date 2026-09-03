<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProduitUnite extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'type_unite',
        'quantite_equivalente_detail',
        'prix_vente_unite',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'prix_vente_unite' => 'decimal:2',
            'actif' => 'boolean',
        ];
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function venteLignes(): HasMany
    {
        return $this->hasMany(VenteLigne::class);
    }
}