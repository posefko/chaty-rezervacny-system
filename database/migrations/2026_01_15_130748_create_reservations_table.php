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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // vzťahy
            $table->foreignId('cottage_id')->constrained('cottages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // termín
            $table->date('date_from');
            $table->date('date_to');

            // info
            $table->unsignedSmallInteger('guests')->default(1);
            $table->string('status')->default('pending'); // pending/confirmed/cancelled
            $table->text('note')->nullable();

            $table->timestamps();

            // ochrana proti duplicitám v rovnakom intervale (základ)
            $table->index(['cottage_id', 'date_from', 'date_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
