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
        Schema::create('quote', function (Blueprint $table) {
            $table->id();
            $table->string('quote_client_name');
            $table->string('quote_client_id');
            $table->string('quote_client_phone');
            $table->string('quote_client_email');
            $table->double('quote_material_total');
            $table->double('quote_work_total');
            $table->double('quote_total');
            $table->date('quote_expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote');
    }
};
