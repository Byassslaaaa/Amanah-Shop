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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // No shop_id - single shop only (Amanah Shop)
            $table->enum('type', ['in', 'out']); // Stock in or out
            $table->integer('quantity');
            $table->integer('stock_before'); // Stock level before this movement
            $table->integer('stock_after'); // Stock level after this movement
            $table->string('reference_type')->nullable(); // e.g., 'App\Models\Order', 'adjustment', 'return'
            $table->unsignedBigInteger('reference_id')->nullable(); // e.g., order_id
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes for performance
            $table->index(['product_id', 'created_at']);
            $table->index('type');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
