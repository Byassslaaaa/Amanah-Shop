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
        Schema::create('about_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Selamat Datang di Amanah Shop');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->integer('years_operating')->default(5);
            $table->integer('happy_customers')->default(1000);
            $table->integer('products_sold')->default(500);
            $table->integer('team_members')->default(10);
            $table->integer('product_variants')->default(200);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};
