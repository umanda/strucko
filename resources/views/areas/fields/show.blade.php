@extends('layouts.master')

@section('meta-description', 'About ' . $field->scientific_field)

@section('title', $field->scientific_field . ' - description')

@section('content')

<h3>{{ $field->scientific_field }}</h3>

<h4>Mark: {{ $field->mark }}</h4>
<h4>Active: {{ $field->active ? 'Yes' : 'No' }} </h4>
<h4>Description:</h4>
<p>{{ $field->description ?: 'No description yet...' }}</p>

<a class="btn btn-default" href="{{ action('ScientificFieldsController@edit', [$field->scientific_area_id, $field->id]) }}">Edit</a>
<a class="btn btn-default" href="{{ action('ScientificBranchesController@index', 
            [$field->scientific_area_id, $field->id])}}">Show branches</a>
<a class="btn btn-default" href="{{ action('ScientificFieldsController@index', [$field->scientific_area_id])}}">Back to all fields</a>
@endsection