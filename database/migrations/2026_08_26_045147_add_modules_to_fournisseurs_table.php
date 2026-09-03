<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE fournisseurs MODIFY module ENUM('secretariat', 'librairie', 'boissons', 'services', 'general') NOT NULL DEFAULT 'general'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE fournisseurs MODIFY module ENUM('librairie', 'boissons', 'general') NOT NULL DEFAULT 'general'");
    }
};