<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPrincipalAdmin(): bool
    {
        return $this->isAdmin()
            && $this->email === env('ADMIN_PRINCIPAL_EMAIL', 'visionmoderneconstructionsarl@gmail.com');
    }

    public function isCaissiere(): bool
    {
        return $this->role === 'caissiere';
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class, 'caissiere_id');
    }

    public function transactionsUnites(): HasMany
    {
        return $this->hasMany(TransactionUnite::class, 'caissiere_id');
    }

    public function transactionsWifi(): HasMany
    {
        return $this->hasMany(TransactionWifi::class, 'caissiere_id');
    }

    public function stocksAlimentes(): HasMany
    {
        return $this->hasMany(StockUnite::class, 'alimente_par');
    }

    public function approvisionnementsCreated(): HasMany
    {
        return $this->hasMany(Approvisionnement::class, 'created_by');
    }
}