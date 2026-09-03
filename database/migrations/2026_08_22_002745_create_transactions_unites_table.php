<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions_unites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_unite_id')->constrained('stocks_unites');
            $table->foreignId('caissiere_id')->constrained('users');
            $table->decimal('montant_transige', 12, 2);
            $table->decimal('benefice', 10, 2);
            $table->text('note')->nullable();
            $table->timestamp('date_transaction')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_unites');
    }
};