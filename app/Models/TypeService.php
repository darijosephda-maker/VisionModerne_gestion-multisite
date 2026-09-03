<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeService extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }

    public function venteLignes(): HasMany
    {
        return $this->hasMany(VenteLigne::class);
    }
}