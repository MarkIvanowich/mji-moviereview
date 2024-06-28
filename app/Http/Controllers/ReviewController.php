<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Movie $movie)
    {
        return view('movies.reviews.create', compact('movie'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Movie $movie)
    {
        $data = $request->validate([
                                       'review' => 'required|string',
                                       'rating' => 'required|min:1|max:5|integer'
                                   ]);
        $movie->reviews()->create($data);
        return redirect()->route('movies.show', $movie);
    }
}
