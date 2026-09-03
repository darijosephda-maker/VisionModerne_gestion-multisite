<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionWifi extends Model
{
    use HasFactory;
    protected $table = 'transactions_wifi';
    protected $fillable = [
        'forfait_id',
        'caissiere_id',
        'montant_vente',
        'benefice',
        'date_transaction',
    ];

    protected function casts(): array
    {
        return [
            'montant_vente' => 'decimal:2',
            'benefice' => 'decimal:2',
            'date_transaction' => 'datetime',
        ];
    }

    public function forfait(): BelongsTo
    {
        return $this->belongsTo(WifiForfait::class, 'forfait_id');
    }

    public function caissiere(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caissiere_id');
    }
}