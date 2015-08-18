@extends('layouts.master')

@section('meta-description', 'About ' . $area->scientific_area)

@section('title', $area->scientific_area . ' - description')

@section('content')

<h3>{{ $area->scientific_area }}</h3>

<h4>Mark: {{ $area->mark }}</h4>
<h4>Active: {{ $area->active ? 'Yes' : 'No' }} </h4>

<h4>Description:</h4>
<p>{{ $area->description ?: 'No description yet...' }}</p>

<a class="btn btn-default" href="{{ action('ScientificAreasController@edit', [$area->id]) }}">Edit</a>
<a class="btn btn-default" href="{{ action('ScientificFieldsController@index', [$area->id])}}">Show fields</a>
<a class="btn btn-default" href="{{ action('ScientificAreasController@index')}}">Back to all areas</a>
<hr>
<form method="POST" action="{{ action('ScientificAreasController@destroy', $area->id) }}">
    {!! csrf_field() !!}
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit" class="btn btn-primary">Delete</button>
</form>
@endsection