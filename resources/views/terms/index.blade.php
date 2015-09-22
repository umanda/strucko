@extends('layouts.master')

@section('meta-description', 'All terms in the Strucko Expert Dictionary.')

@section('title', 'All terms')

@section('content')
    
    @include('layouts.filter')
    
    @if(isset($menuLetters) && ! ($menuLetters->isEmpty()))
        @include('layouts.menu')
        @include('layouts.search')
    @elseif (isset($allFilters['language_id']) && isset($allFilters['scientific_field_id']))
        <br>
        <p>Sorry, no terms available for selected language and field...</p>
    @else
        <br>
        <p>Please select language and field...</p> 
    @endif
    <i>TODO nekako postavi translate_to. Postavi to da se ne može prijevod postaviti na isti jezik </i>
    @if(isset($terms))
        @foreach($terms as $term)
            <h2><a href="{{ action('TermsController@show', ['slug' =>
                    $term->slug, isset($allFilters['translate_to']) ? 'translate_to=' . $allFilters['translate_to'] : '']) }}">{{ $term->term }}</a>
            </h2>
            <p>Slug: {{ $term->slug }}</p>
        @endforeach
    @endif
@endsection