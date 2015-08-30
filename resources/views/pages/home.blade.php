@extends('layouts.master')

@section('meta-description', 'Welcome to the Strucko - the Expert Dictionary')

@section('title', 'Strucko - the Expert Dictionary')

@section('content')
    <h2>Welcome to the Strucko - The Expert Dictionary </h2>
    <a href="{{ action('TermsController@filter', ['language_id' => 'hrv', 'scientific_field_id' => 19]) }}">Filter test</a>
@endsection


