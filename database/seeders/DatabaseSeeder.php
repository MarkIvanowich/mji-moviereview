<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Movie::factory(24)->create()->each(function (Movie $movie) {
            $numReviews = random_int(5, 30);
            Review::factory()->count($numReviews)->make_review_spectrum(5)->for($movie)->create();
        });
        Movie::factory(69)->create()->each(function (Movie $movie) {
            $numReviews = random_int(5, 30);
            Review::factory()->count($numReviews)->make_review_spectrum(4)->for($movie)->create();
        });
        Movie::factory(10)->create()->each(function (Movie $movie) {
            $numReviews = random_int(5, 30);
            Review::factory()->count($numReviews)->make_review_spectrum(1)->for($movie)->create();
        });
    }
}
