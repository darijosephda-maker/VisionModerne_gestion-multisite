<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approvisionnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'produit_id',
        'quantite',
        'prix_unitaire_achat',
        'montant_total',
        'statut_paiement',
        'montant_paye',
        'created_by',
        'date_approvisionnement',
    ];

    protected function casts(): array
    {
        return [
            'prix_unitaire_achat' => 'decimal:2',
            'montant_total' => 'decimal:2',
            'montant_paye' => 'decimal:2',
            'date_approvisionnement' => 'datetime',
        ];
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function soldeDu(): float
    {
        return $this->montant_total - $this->montant_paye;
    }
}