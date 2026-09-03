<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->renameColumn('contact', 'telephone');
        });

        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->string('contact_personne')->nullable()->after('nom');
            $table->string('localite')->nullable()->after('adresse');
            $table->text('produits_fournis')->nullable()->after('module');
            $table->text('notes')->nullable()->after('produits_fournis');
        });
    }

    public function down(): void
    {
        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->dropColumn(['contact_personne', 'localite', 'produits_fournis', 'notes']);
        });

        Schema::table('fournisseurs', function (Blueprint $table) {
            $table->renameColumn('telephone', 'contact');
        });
    }
};