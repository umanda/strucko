@extends('layouts.master')

@section('meta-description', 'All terms in the Strucko Expert Dictionary.')

@section('title', 'All terms')

@section('content')
    @foreach($terms as $term)
        <h2>{{ $term->term }}</h2>
    @endforeach
@endsection