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
        Schema::create('quote_material', function (Blueprint $table) {
            $table->foreignId('quote_id')->references('id')->on('quote');
            $table->foreignId('product_id')->references('id')->on('product');
            $table->primary(['quote_id', 'product_id']);
            $table->double('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_material');
    }
};
