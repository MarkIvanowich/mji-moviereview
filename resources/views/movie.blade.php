@extends('layouts.app')

@section('content')
  <div class="mb-4">
    <h1 class="sticky top-0 mb-2 text-2xl">{{ $movie->title }}</h1>

    <div class="movie-info">
      <div class="movie-author mb-4 text-lg font-semibold">by {{ $movie->author }}</div>
      <div class="movie-rating flex items-center">
        <div class="mr-2 text-sm font-medium text-slate-700">
          {{ number_format($movie->reviews_avg_rating, 1) }}
        </div>
        <span class="movie-review-count text-sm text-gray-500">
          {{ $movie->reviews_count }} {{ Str::plural('review', 5) }}
        </span>
      </div>
    </div>
  </div>

  <div>
    <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
    <ul>
      @forelse ($movie->reviews as $review)
        <li class="movie-item mb-4">
          <div>
            <div class="mb-2 flex items-center justify-between">
              <div class="font-semibold">{{ $review->rating }}</div>
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
