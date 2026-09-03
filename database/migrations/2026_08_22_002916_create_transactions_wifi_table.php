<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions_wifi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forfait_id')->constrained('wifi_forfaits');
            $table->foreignId('caissiere_id')->constrained('users');
            $table->decimal('montant_vente', 10, 2);
            $table->decimal('benefice', 10, 2);
            $table->timestamp('date_transaction')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_wifi');
    }
};