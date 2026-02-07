<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds performance indexes for frequently queried columns.
     */
    public function up(): void
    {
        // Helper function to check if index exists
        $indexExists = function ($table, $indexName) {
            $connection = Schema::getConnection();
            $databaseName = $connection->getDatabaseName();

            $result = DB::select(
                "SELECT COUNT(*) as count FROM information_schema.statistics
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$databaseName, $table, $indexName]
            );

            return $result[0]->count > 0;
        };

        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('orders', 'orders_user_id_index')) {
                $table->index('user_id', 'orders_user_id_index');
            }
            if (!$indexExists('orders', 'orders_status_index')) {
                $table->index('status', 'orders_status_index');
            }
            if (!$indexExists('orders', 'orders_payment_status_index')) {
                $table->index('payment_status', 'orders_payment_status_index');
            }
            if (!$indexExists('orders', 'orders_order_number_index')) {
                $table->index('order_number', 'orders_order_number_index');
            }
            if (!$indexExists('orders', 'orders_user_status_index')) {
                $table->index(['user_id', 'status'], 'orders_user_status_index');
            }
        });

        // Carts table indexes
        Schema::table('carts', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('carts', 'carts_user_selected_index')) {
                $table->index(['user_id', 'is_selected'], 'carts_user_selected_index');
            }
            if (!$indexExists('carts', 'carts_user_product_index')) {
                $table->index(['user_id', 'product_id'], 'carts_user_product_index');
            }
        });

        // Shipping addresses table indexes
        Schema::table('shipping_addresses', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('shipping_addresses', 'shipping_addresses_user_id_index')) {
                $table->index('user_id', 'shipping_addresses_user_id_index');
            }
        });

        // Order items table indexes
        Schema::table('order_items', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('order_items', 'order_items_order_id_index')) {
                $table->index('order_id', 'order_items_order_id_index');
            }
            if (!$indexExists('order_items', 'order_items_product_id_index')) {
                $table->index('product_id', 'order_items_product_id_index');
            }
        });

        // Installment payments table indexes
        Schema::table('installment_payments', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('installment_payments', 'installment_payments_order_id_index')) {
                $table->index('order_id', 'installment_payments_order_id_index');
            }
            if (!$indexExists('installment_payments', 'installment_payments_status_due_index')) {
                $table->index(['status', 'due_date'], 'installment_payments_status_due_index');
            }
        });

        // Inventory movements table indexes
        Schema::table('inventory_movements', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('inventory_movements', 'inventory_movements_product_id_index')) {
                $table->index('product_id', 'inventory_movements_product_id_index');
            }
            if (!$indexExists('inventory_movements', 'inventory_movements_type_index')) {
                $table->index('type', 'inventory_movements_type_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Helper function to check if index exists
        $indexExists = function ($table, $indexName) {
            $connection = Schema::getConnection();
            $databaseName = $connection->getDatabaseName();

            $result = DB::select(
                "SELECT COUNT(*) as count FROM information_schema.statistics
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$databaseName, $table, $indexName]
            );

            return $result[0]->count > 0;
        };

        Schema::table('orders', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('orders', 'orders_user_id_index')) {
                $table->dropIndex('orders_user_id_index');
            }
            if ($indexExists('orders', 'orders_status_index')) {
                $table->dropIndex('orders_status_index');
            }
            if ($indexExists('orders', 'orders_payment_status_index')) {
                $table->dropIndex('orders_payment_status_index');
            }
            if ($indexExists('orders', 'orders_order_number_index')) {
                $table->dropIndex('orders_order_number_index');
            }
            if ($indexExists('orders', 'orders_user_status_index')) {
                $table->dropIndex('orders_user_status_index');
            }
        });

        Schema::table('carts', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('carts', 'carts_user_selected_index')) {
                $table->dropIndex('carts_user_selected_index');
            }
            if ($indexExists('carts', 'carts_user_product_index')) {
                $table->dropIndex('carts_user_product_index');
            }
        });

        Schema::table('shipping_addresses', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('shipping_addresses', 'shipping_addresses_user_id_index')) {
                $table->dropIndex('shipping_addresses_user_id_index');
            }
        });

        Schema::table('order_items', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('order_items', 'order_items_order_id_index')) {
                $table->dropIndex('order_items_order_id_index');
            }
            if ($indexExists('order_items', 'order_items_product_id_index')) {
                $table->dropIndex('order_items_product_id_index');
            }
        });

        Schema::table('installment_payments', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('installment_payments', 'installment_payments_order_id_index')) {
                $table->dropIndex('installment_payments_order_id_index');
            }
            if ($indexExists('installment_payments', 'installment_payments_status_due_index')) {
                $table->dropIndex('installment_payments_status_due_index');
            }
        });

        Schema::table('inventory_movements', function (Blueprint $table) use ($indexExists) {
            if ($indexExists('inventory_movements', 'inventory_movements_product_id_index')) {
                $table->dropIndex('inventory_movements_product_id_index');
            }
            if ($indexExists('inventory_movements', 'inventory_movements_type_index')) {
                $table->dropIndex('inventory_movements_type_index');
            }
        });
    }
};
