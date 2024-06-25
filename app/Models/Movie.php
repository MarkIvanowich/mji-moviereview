<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'producer'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /*
     * Local query scopes are not something I've ever done before.
     * Look at me learning! 2024-06-24
     */
    public function scopeTitle(Builder $query, $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
                                     'reviews' => fn(Builder $q) => $this->dateRangeLimiter($q, $from, $to)
                                 ])->orderByDesc('reviews_count');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg([
                                   'reviews' => fn(Builder $q) => $this->dateRangeLimiter($q, $from, $to)
                               ], 'rating')->orderByDesc('reviews_avg_rating');
    }

    private function dateRangeLimiter(Builder $internalquery, $from = null, $to = null)
    {
        if ($from && !$to) {
            // from but no to
            $internalquery->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            // to but no from
            $internalquery->where('created_at', '<=', $to);
        } else {
            // from and to
            $internalquery->whereBetween('created_at', [$from, $to]);
        }
    }

    /*
     * Sidenote: I feel very strange about passing an array with a function into methods like withAvg
     * Probably because I know this is creating subqueries which make the SQL more complex. I want easy to debug!
     *
     * Also I need to revisit my SQL books regarding using the HAVING clause in aggregate functions.
     */

}
