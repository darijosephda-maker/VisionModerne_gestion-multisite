<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'caissiere_id',
        'module',
        'montant_total',
        'client_nom',
        'client_prenom',
        'client_telephone',
        'statut',
        'date_vente',
    ];

    protected function casts(): array
    {
        return [
            'montant_total' => 'decimal:2',
            'date_vente' => 'datetime',
        ];
    }

    public function caissiere(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caissiere_id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(VenteLigne::class);
    }
}