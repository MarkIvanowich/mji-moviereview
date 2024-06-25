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


}
