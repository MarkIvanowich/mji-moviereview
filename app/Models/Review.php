<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    protected static function booted()
    {
        static::updated(
            function (Review $review) {
                return cache()->forget('movie:' . $review->movie_id);
            }
        );
        static::deleted(
            function (Review $review) {
                return cache()->forget('movie:' . $review->movie_id);
            }
        );
        /*
         * We are going to ignore forgetting cached movies on the index view, because the reviews are averages.
         * Constantly invalidating a cache for a large query defeats the purpose of having cache in the first place.
         */
    }
}
