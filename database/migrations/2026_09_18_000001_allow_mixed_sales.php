<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE ventes MODIFY module ENUM('secretariat', 'librairie', 'boissons', 'services', 'mixte') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE ventes MODIFY module ENUM('secretariat', 'librairie', 'boissons', 'services') NOT NULL");
        }
    }
};