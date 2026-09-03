<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WifiForfait extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_forfait',
        'prix_cout',
        'prix_vente',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'prix_cout' => 'decimal:2',
            'prix_vente' => 'decimal:2',
            'actif' => 'boolean',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionWifi::class, 'forfait_id');
    }

    public function marge(): float
    {
        return $this->prix_vente - $this->prix_cout;
    }
}