<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();


            $table->text('review');
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            // Define relationships
//            $table->unsignedBigInteger('movie_id');
//            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade'); // EXPLICIT
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
