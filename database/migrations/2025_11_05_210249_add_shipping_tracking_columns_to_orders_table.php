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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_courier', 50)->nullable()->after('shipping_service')->comment('Kurir: jne, jnt, sicepat, dll');
            $table->string('shipping_resi', 100)->nullable()->after('shipping_courier')->comment('Nomor resi');
            $table->string('shipping_status', 50)->nullable()->after('shipping_resi')->comment('Status tracking: pending, on_process, delivered, dll');
            $table->text('shipping_history')->nullable()->after('shipping_status')->comment('History tracking JSON');
            $table->timestamp('shipped_at')->nullable()->after('shipping_history')->comment('Tanggal input resi');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at')->comment('Tanggal delivered');
            $table->timestamp('tracking_updated_at')->nullable()->after('delivered_at')->comment('Last tracking update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier',
                'shipping_resi',
                'shipping_status',
                'shipping_history',
                'shipped_at',
                'delivered_at',
                'tracking_updated_at'
            ]);
        });
    }
};
