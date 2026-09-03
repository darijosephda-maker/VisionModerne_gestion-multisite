<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caissiere_id')->constrained('users')->cascadeOnDelete();
            $table->enum('module', ['secretariat', 'librairie', 'boissons']);
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('statut', ['validee', 'annulee'])->default('validee');
            $table->timestamp('date_vente')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};