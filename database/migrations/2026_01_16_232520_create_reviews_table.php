<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cottage_id')->constrained('cottages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();

            $table->timestamps();

            // 1 user môže hodnotiť 1 chatu max raz (voliteľné, ale odporúčam)
            $table->unique(['cottage_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
