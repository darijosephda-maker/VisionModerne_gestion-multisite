<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_contenu', function (Blueprint $table) {
            $table->id();
            $table->string('cle')->unique();
            $table->string('titre')->nullable();
            $table->text('contenu')->nullable();
            $table->integer('ordre_affichage')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contenu');
    }
};