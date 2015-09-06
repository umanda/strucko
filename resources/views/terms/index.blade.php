@extends('layouts.master')

@section('meta-description', 'All terms in the Strucko Expert Dictionary.')

@section('title', 'All terms')

@section('content')
    
    @include('layouts.filter')
    
    @if(isset($menuLetters) && ! ($menuLetters->isEmpty()))
        @include('layouts.menu')
        @include('layouts.search')
    @elseif (isset($filters['language_id']) && isset($filters['scientific_field_id']))
        <br>
        <p>Sorry, no terms available for selected language and field...</p>
    @else
        <br>
        <p>Please select language and field...</p> 
    @endif
    
    @if(isset($terms))
        @foreach($terms as $term)
            <h2><a href="{{ action('TermsController@show', ['slug_unique' =>
                    $term->slug_unique]) }}">{{ $term->term }}</a>
            </h2>
            <p>Abbreviation: {{ $term->abbreviation }}</p>
            <p>Slug: {{ $term->slug }}</p>
            <p>Slug Unique: {{ $term->slug_unique }}</p>
        @endforeach
    @endif
@endsection