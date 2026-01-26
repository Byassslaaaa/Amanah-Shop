<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop village-related foreign keys first

        // Drop foreign keys from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        // Drop foreign keys from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        // Drop foreign keys from order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        // Drop village_id columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('village_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('village_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('village_id');
        });

        // Drop village-related tables
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('village_transactions');
        Schema::dropIfExists('village_balances');
        Schema::dropIfExists('villages');

        // Simplify user roles - remove village admin concept
        // Now only: superadmin, admin, user
        // "admin" = staff Amanah Shop (not tied to specific village)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate villages table
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('origin_province_id')->nullable();
            $table->string('origin_province_name')->nullable();
            $table->string('origin_city_id')->nullable();
            $table->string('origin_city_name')->nullable();
            $table->string('origin_postal_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('cover_photo')->nullable();
            $table->timestamps();
        });

        // Add back village_id columns
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('village_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('village_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('village_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Recreate village_balances
        Schema::create('village_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('available_balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('total_withdrawn', 15, 2)->default(0);
            $table->timestamps();
        });

        // Recreate village_transactions
        Schema::create('village_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Recreate withdrawal_requests
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
};
