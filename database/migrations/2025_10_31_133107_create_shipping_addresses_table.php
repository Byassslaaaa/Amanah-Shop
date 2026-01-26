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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label')->default('Rumah'); // Rumah, Kantor, dll
            $table->string('recipient_name');
            $table->string('phone');
            $table->string('province_id'); // ID Provinsi dari Raja Ongkir
            $table->string('province_name');
            $table->string('city_id'); // ID Kota dari Raja Ongkir
            $table->string('city_name');
            $table->string('district')->nullable(); // Kecamatan
            $table->string('postal_code');
            $table->text('full_address'); // Alamat lengkap
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
