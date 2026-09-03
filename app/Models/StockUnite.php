<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockUnite extends Model
{
    use HasFactory;
    protected $table = 'stocks_unites';
    protected $fillable = [
        'operateur',
        'capital_initial',
        'solde_actuel',
        'seuil_alerte',
        'alimente_par',
        'date_alimentation',
    ];

    protected function casts(): array
    {
        return [
            'capital_initial' => 'decimal:2',
            'solde_actuel' => 'decimal:2',
            'seuil_alerte' => 'decimal:2',
            'date_alimentation' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alimente_par');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionUnite::class);
    }

    public function soldeBas(): bool
    {
        return $this->solde_actuel <= $this->seuil_alerte;
    }
}