@if ($rating)
  @for ($i = 1; $i <= 5; $i++)
    {{ $i <= round($rating) ? '★' : '☆' }}
  @endfor
@else
  No rating
@endif
{{--
The instructor shows creating a StarComponent, which is unnecessary since laravel understand anonymous components
The only time I can think of explictly creating a component if there are dependencies or backend logic that interacts closely with a model or request.
--}}
