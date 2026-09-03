<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wifi_forfaits', function (Blueprint $table) {
            $table->id();
            $table->string('nom_forfait');
            $table->decimal('prix_cout', 10, 2);
            $table->decimal('prix_vente', 10, 2);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wifi_forfaits');
    }
};