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
        Schema::create('village_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained()->onDelete('cascade');
            $table->decimal('total_balance', 15, 2)->default(0)->comment('Total saldo desa');
            $table->decimal('available_balance', 15, 2)->default(0)->comment('Saldo yang bisa ditarik');
            $table->decimal('pending_balance', 15, 2)->default(0)->comment('Saldo dari order yang belum selesai');
            $table->decimal('total_withdrawn', 15, 2)->default(0)->comment('Total yang sudah ditarik');
            $table->timestamps();

            // Index
            $table->unique('village_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_balances');
    }
};
