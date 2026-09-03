<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->string('client_nom')->nullable()->after('module');
            $table->string('client_prenom')->nullable()->after('client_nom');
            $table->string('client_telephone')->nullable()->after('client_prenom');
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn(['client_nom', 'client_prenom', 'client_telephone']);
        });
    }
};
