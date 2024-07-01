<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter');

        $movies = Movie::when($title, function ($query, $title) {
            $query->title($title); // Model::scopeTitle
        });
        $movies = match ($filter) { //switch
            'popular_last_month' => $movies->popularLastMonth(),
            'popular_last_6months' => $movies->popularLast6Months(),
            'highest_rated_last_month' => $movies->highestRatedLastMonth(),
            'highest_rated_last_6months' => $movies->highestRatedLast6Months(),
            default => $movies->latest()
        };

        $cache_key = 'movies:' . $filter . ":" . $title;
        $movies = cache()->remember(
            $cache_key,
            3600,
            function () use ($movies) {
                return $movies->withTotalAvgRating()->withTotalReviewsCount()->get();
                // places the total number and average ratings of all reviews in the eloquent model untouched by the filters
            }
        );

        return view('movies.index', compact('movies', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * I have to do this differently from the instructor yet again.
     * The withTotal... methods add on to the generated query. Model Binding leads cache() to assume I'm trying to cache the query (PDO)
     */
    public function show(int $id)
    {
        $cache_key = 'movies:' . $id;
        $movie = cache()->remember($cache_key, 3600, function () use ($id) {
            return Movie::with(['reviews' => fn($q) => $q->latest()])
                ->withTotalAvgRating()
                ->withTotalReviewsCount()
                ->findOrFail($id);
        });
        return view('movies.show', compact('movie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
