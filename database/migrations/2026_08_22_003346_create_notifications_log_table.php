<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['stock_bas', 'rapport_quotidien', 'action_sensible', 'recharge_unite']);
            $table->string('destinataire_email');
            $table->text('contenu')->nullable();
            $table->timestamp('envoye_le')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_log');
    }
};