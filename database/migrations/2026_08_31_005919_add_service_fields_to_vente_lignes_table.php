<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vente_lignes', function (Blueprint $table) {
            $table->foreignId('produit_id')->nullable()->change();
            $table->foreignId('type_service_id')->nullable()->after('produit_unite_id')->constrained('type_services')->nullOnDelete();
            $table->string('description_libre')->nullable()->after('type_service_id');
        });
    }

    public function down(): void
    {
        Schema::table('vente_lignes', function (Blueprint $table) {
            $table->dropForeign(['type_service_id']);
            $table->dropColumn(['type_service_id', 'description_libre']);
            $table->foreignId('produit_id')->nullable(false)->change();
        });
    }
};