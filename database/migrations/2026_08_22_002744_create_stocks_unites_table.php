<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks_unites', function (Blueprint $table) {
            $table->id();
            $table->string('operateur');
            $table->decimal('capital_initial', 12, 2);
            $table->decimal('solde_actuel', 12, 2);
            $table->decimal('seuil_alerte', 12, 2)->default(5000);
            $table->foreignId('alimente_par')->constrained('users');
            $table->timestamp('date_alimentation')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks_unites');
    }
};