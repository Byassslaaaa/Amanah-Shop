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
        Schema::table('villages', function (Blueprint $table) {
            $table->string('origin_province_id')->nullable()->after('address');
            $table->string('origin_province_name')->nullable()->after('origin_province_id');
            $table->string('origin_city_id')->nullable()->after('origin_province_name');
            $table->string('origin_city_name')->nullable()->after('origin_city_id');
            $table->string('origin_postal_code')->nullable()->after('origin_city_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn([
                'origin_province_id',
                'origin_province_name',
                'origin_city_id',
                'origin_city_name',
                'origin_postal_code'
            ]);
        });
    }
};
