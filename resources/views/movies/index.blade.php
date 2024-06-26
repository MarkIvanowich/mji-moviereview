@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">MJI Movie Reviews</h1>

    <form method="GET" action="{{ route('movies.index') }}" class="mb-4 flex items-center space-x-2">
        <input type="text" name="title" placeholder="Search by title"
               value="{{ request('title') }}" class="input h-10"/>
        <input type="hidden" name="filter" value="{{ request('filter') }}"/>
        <button type="submit" class="btn h-10">Search</button>
        <a href="{{ route('movies.index') }}" class="btn h-10">Clear</a>
    </form>

    <div class="filter-container mb-4 flex">
        @php
            $filters = [
                '' => 'Latest',
                'popular_last_month' => 'Popular Last Month',
                'popular_last_6months' => 'Popular Last 6 Months',
                'highest_rated_last_month' => 'Highest Rated Last Month',
                'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
            ];
        @endphp

        @foreach ($filters as $key => $label)
            <a href="{{ route('movies.index', [...request()->query(), 'filter' => $key]) }}" {{-- [...request() is something new to php8. VERY USEFUL! --}}
            class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <ul>
        @forelse ($movies as $movie)
            <li class="mb-4">
                <div class="movie-item">
                    <div
                            class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('movies.show', $movie) }}" class="movie-title">{{ $movie->title }}</a>
                            <span class="movie-director">directed by {{ $movie->director }}</span>
                        </div>
                        @if($movie->reviews_count)
                            {{-- Only shows when filtering --}}
                            <div class="mr-5">
                                <div class="movie-rating">
                                    <x-star-rating :rating="$movie->reviews_avg_rating"/>
                                </div>
                                <div class="movie-review-count">
                                    out of {{$movie->reviews_count}} {{ Str::plural('review', $movie->reviews_count) }}
                                </div>
                            </div>
                        @endif
                        <div class="">
                            {{--
                            Always shows, filtering or not.
                            ~
                            $unqiue_filtered represents the case where the user has a filter turned on AND the number of filtered reviews is different than the total reviews. Essentially we want to avoid repeating ourselves: "x of y reviews... x out of y all time"
                            --}}
                            @php($unique_filtered = $movie->all_reviews_count!=$movie->reviews_count&&$movie->reviews_count)
                            <div class="movie-rating">
                                <x-star-rating :rating="$movie->all_reviews_rating"/>
                            </div>
                            <div class="movie-review-count">
                                {{$unique_filtered?"of":"out of"}}{{----}}
                                {{$movie->all_reviews_count?:"zero" }}
                                {{$unique_filtered?"all-time":Str::plural('review', $movie->all_reviews_count)}}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-movie-item">
                    <p class="empty-text">No movies found</p>
                    <a href="{{ route('movies.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
