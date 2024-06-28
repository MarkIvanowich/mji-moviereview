<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'director'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /*
     *  This function never uses the $from or $to paramters, so the DateRangeLimiter was useless
     */
    public function scopeWithTotalReviewsCount(Builder $query): Builder|QueryBuilder
    {
        return $query->withCount('reviews as all_reviews_count');
    }

    public function scopeWithTotalAvgRating(Builder $query): Builder|QueryBuilder
    {
        return $query->withAvg('reviews as all_reviews_rating', 'rating');
    }


    /*
     * Local query scopes are not something I've ever done before.
     * Look at me learning! 2024-06-24
     */
    public function scopeTitle(Builder $query, $title): Builder|QueryBuilder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
                                     'reviews' => fn(Builder $q) => $this->dateRangeLimiter($q, $from, $to)
                                 ])->orderByDesc('reviews_count');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
                                   'reviews' => fn(Builder $q) => $this->dateRangeLimiter($q, $from, $to)
                               ], 'rating')->orderByDesc('reviews_avg_rating');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeLimiter(Builder $internalquery, $from = null, $to = null)
    {
        if ($from && !$to) {
            // from but no to
            $internalquery->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            // to but no from
            $internalquery->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            // with both from and to
            $internalquery->whereBetween('created_at', [$from, $to]);
        }
        //else with neither
    }

    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }

    /*
     * Sidenote: I feel very strange about passing an array with a function into methods like withAvg
     * Probably because I know this is creating subqueries which make the SQL more complex. I want easy to follow queries!
     *
     * Also I need to revisit my SQL books regarding using the HAVING clause in aggregate functions.
     */

}
