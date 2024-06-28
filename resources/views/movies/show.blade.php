@extends('layouts.app')

@section('content')
  <div class="mb-4">
    <h1 class="mb-2 text-2xl">{{ $movie->title }}</h1>

    <div class="movie-info">
      <div class="movie-director mb-4 text-lg font-semibold">by {{ $movie->director }}</div>
      <div class="movie-rating flex items-center">
        <div class="mr-2 text-sm font-medium text-slate-700">
          <x-star-rating :rating="$movie->reviews_avg_rating" />
        </div>
        <span class="movie-review-count text-sm text-gray-500">
          {{ $movie->reviews_count }} {{ Str::plural('review', $movie->reviews_count) }}
        </span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <a href="{{ route('movies.reviews.create', $movie) }}" class="reset-link">
      Add a review!</a>
  </div>

  <div>
    <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
    <ul>
      @forelse ($movie->reviews as $review)
        <li class="movie-item mb-4">
          <div>
            <div class="mb-2 flex items-center justify-between">
              <div class="font-semibold">
                <x-star-rating :rating="$review->rating" />
              </div>
              <div class="movie-review-count">
                {{ $review->created_at->format('M j, Y') }}</div>
            </div>
            <p class="text-gray-700">{{ $review->review }}</p>
          </div>
        </li>
      @empty
        <li class="mb-4">
          <div class="empty-movie-item">
            <p class="empty-text text-lg font-semibold">No reviews yet</p>
          </div>
        </li>
      @endforelse
    </ul>
  </div>
@endsection
