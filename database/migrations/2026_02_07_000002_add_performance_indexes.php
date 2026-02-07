<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds performance indexes for frequently queried columns.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id', 'orders_user_id_index');
            $table->index('status', 'orders_status_index');
            $table->index('payment_status', 'orders_payment_status_index');
            $table->index('order_number', 'orders_order_number_index');
            $table->index(['user_id', 'status'], 'orders_user_status_index');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->index(['user_id', 'is_selected'], 'carts_user_selected_index');
            $table->index(['user_id', 'product_id'], 'carts_user_product_index');
        });

        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->index('user_id', 'shipping_addresses_user_id_index');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id', 'order_items_order_id_index');
            $table->index('product_id', 'order_items_product_id_index');
        });

        Schema::table('installment_payments', function (Blueprint $table) {
            $table->index('order_id', 'installment_payments_order_id_index');
            $table->index(['status', 'due_date'], 'installment_payments_status_due_index');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->index('product_id', 'inventory_movements_product_id_index');
            $table->index('type', 'inventory_movements_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_id_index');
            $table->dropIndex('orders_status_index');
            $table->dropIndex('orders_payment_status_index');
            $table->dropIndex('orders_order_number_index');
            $table->dropIndex('orders_user_status_index');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('carts_user_selected_index');
            $table->dropIndex('carts_user_product_index');
        });

        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->dropIndex('shipping_addresses_user_id_index');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_id_index');
            $table->dropIndex('order_items_product_id_index');
        });

        Schema::table('installment_payments', function (Blueprint $table) {
            $table->dropIndex('installment_payments_order_id_index');
            $table->dropIndex('installment_payments_status_due_index');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropIndex('inventory_movements_product_id_index');
            $table->dropIndex('inventory_movements_type_index');
        });
    }
};
