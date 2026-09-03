<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $table = 'notifications_log';

    protected $fillable = [
        'type',
        'destinataire_email',
        'contenu',
        'envoye_le',
    ];

    protected function casts(): array
    {
        return [
            'envoye_le' => 'datetime',
        ];
    }
}