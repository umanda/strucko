@extends('layouts.master')

@section('meta-description', 'All terms in the Strucko Expert Dictionary.')

@section('title', 'All terms')

@section('content')

@include('layouts.filter')

@if(isset($menuLetters) && ! ($menuLetters->isEmpty()))
<div class="row">
    <div class="col-sm-8">
        @include('layouts.menu')
    </div>
    <div class="col-sm-4">
        @include('layouts.search')
    </div>
</div>
<hr>
@elseif (isset($allFilters['language_id']) && isset($allFilters['scientific_field_id']))
<br>
<p>Sorry, no terms available for selected language and field...</p>
@else
<br>
<p>Please select language and field...</p> 
@endif
<i>TODO Postavi to da se ne može prijevod postaviti na isti jezik </i>
@if(isset($terms))
@foreach($terms as $term)
<h2>
    @if (isset($allFilters['translate_to']))
    <a href="{{ action('TermsController@show', ['slug' =>
                    $term->slug, 'translate_to' => $allFilters['translate_to'] ]) }}">{{ $term->term }}</a>
    @else
    <a href="{{ action('TermsController@show', ['slug' => $term->slug]) }}">{{ $term->term }}</a>
    @endif

</h2>
<p>Slug: {{ $term->slug }}</p>
@endforeach
@endif
@endsection