<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL/MariaDB, we need to use raw SQL to modify ENUM
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
