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
        Schema::create('cottages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // názov chaty
            $table->string('location');             // lokalita
            $table->unsignedInteger('capacity');    // kapacita
            $table->unsignedInteger('price_per_night'); // cena / noc
            $table->boolean('is_whole_chalet')->default(true); // celá chata vs lôžka
            $table->text('description')->nullable(); // popis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cottages');
    }
};
