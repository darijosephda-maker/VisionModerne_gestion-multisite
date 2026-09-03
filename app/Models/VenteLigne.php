<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenteLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'produit_id',
        'produit_unite_id',
        'type_service_id',
        'description_libre',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected function casts(): array
    {
        return [
            'prix_unitaire' => 'decimal:2',
            'sous_total' => 'decimal:2',
        ];
    }

    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function produitUnite(): BelongsTo
    {
        return $this->belongsTo(ProduitUnite::class);
    }

    public function typeService(): BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }

    public function estUnService(): bool
    {
        return $this->type_service_id !== null || $this->description_libre !== null;
    }
}