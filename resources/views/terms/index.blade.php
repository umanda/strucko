@extends('layouts.master')

@section('meta-description', 'All terms in the Strucko Expert Dictionary.')

@section('title', 'All terms')

@section('content')
    @foreach($terms as $term)
    <h2><a href="{{ action('TermsController@show', ['slug_unique' =>
                $term->slug_unique]) }}">{{ $term->term }}</a></h2>
        <p>Abbreviation: {{ $term->abbreviation }}</p>
        <p>Slug: {{ $term->slug }}</p>
        <p>Slug Unique: {{ $term->slug_unique }}</p>
    @endforeach
@endsection