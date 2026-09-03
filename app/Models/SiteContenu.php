<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContenu extends Model
{
    use HasFactory;

    protected $table = 'site_contenu';

    protected $fillable = [
        'cle',
        'titre',
        'contenu',
        'ordre_affichage',
    ];

    public static function valeur(string $cle, string $defaut = ''): string
    {
        return static::where('cle', $cle)->value('contenu') ?? $defaut;
    }
}