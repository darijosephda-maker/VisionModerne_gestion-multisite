<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionUnite extends Model
{

    use HasFactory;
    protected $table = 'transactions_unites';
    protected $fillable = [
        'stock_unite_id',
        'caissiere_id',
        'montant_transige',
        'benefice',
        'note',
        'date_transaction',
    ];

    protected function casts(): array
    {
        return [
            'montant_transige' => 'decimal:2',
            'benefice' => 'decimal:2',
            'date_transaction' => 'datetime',
        ];
    }

    public function stockUnite(): BelongsTo
    {
        return $this->belongsTo(StockUnite::class);
    }

    public function caissiere(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caissiere_id');
    }
}