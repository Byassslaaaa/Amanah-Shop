<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations..
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Shipping fields
            $table->foreignId('shipping_address_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->integer('shipping_cost')->default(0)->after('total_amount');
            $table->string('shipping_service')->nullable()->after('shipping_cost'); // JNE REG, JNE YES, dll
            $table->string('shipping_etd')->nullable()->after('shipping_service'); // Estimasi pengiriman

            // Midtrans fields
            $table->string('midtrans_order_id')->nullable()->after('order_number');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_transaction_status')->nullable()->after('midtrans_transaction_id');
            $table->text('midtrans_snap_token')->nullable()->after('midtrans_transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_address_id']);
            $table->dropColumn([
                'shipping_address_id',
                'shipping_cost',
                'shipping_service',
                'shipping_etd',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_transaction_status',
                'midtrans_snap_token'
            ]);
        });
    }
};
