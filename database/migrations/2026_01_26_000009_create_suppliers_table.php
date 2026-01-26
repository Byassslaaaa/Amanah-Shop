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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // SUP001
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add supplier_id to inventory_movements
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('product_id')->constrained()->onDelete('set null');
            $table->string('document_number')->nullable()->after('notes'); // PO number, Invoice number, etc
            $table->decimal('unit_price', 10, 2)->nullable()->after('document_number'); // Harga beli per unit
            $table->decimal('total_price', 12, 2)->nullable()->after('unit_price'); // Total harga pembelian
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['supplier_id', 'document_number', 'unit_price', 'total_price']);
        });

        Schema::dropIfExists('suppliers');
    }
};
