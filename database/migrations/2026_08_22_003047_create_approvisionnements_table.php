<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
            $table->foreignId('produit_id')->constrained('produits');
            $table->integer('quantite');
            $table->decimal('prix_unitaire_achat', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut_paiement', ['paye', 'partiel', 'impaye'])->default('paye');
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('date_approvisionnement')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvisionnements');
    }
};