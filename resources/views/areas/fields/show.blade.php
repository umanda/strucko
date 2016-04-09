@extends('layouts.master')

@section('meta-description', 'About ' . $field->scientific_field)

@section('title', $field->scientific_field . ' - description')

@section('content')
<div class="col-md-8">
    <a class="btn btn-default" 
       href="{{ action('ScientificAreasController@show', $field->scientific_area_id)}}">
        Back to {{ $field->scientificArea->scientific_area }}
    </a>
    <h2>{{ $field->scientific_field }}</h2>
    <h3>Area: {{ $field->scientificArea->scientific_area }}</h3>
    <h3>Mark: {{ $field->mark }}</h3>
    <h3>Active: {{ $field->active ? 'Yes' : 'No' }} </h3>
    <h3>Description:</h3>
    <p>{{ $field->description ?: 'No description yet...' }}</p>

    @if ( Auth::check() && ! (Auth::user()->role_id < 1000) )
    <form method="POST" action="{{ action('ScientificFieldsController@destroy', [$field->scientific_area_id, $field->id]) }}">
        {!! csrf_field() !!}
        {!! getLocaleInputField() !!}
        <input type="hidden" name="_method" value="DELETE">
        <a class="btn btn-default" href="{{ resolveUrlAsAction('ScientificFieldsController@edit', [$field->scientific_area_id, $field->id]) }}">Edit field</a>
        <button type="submit" class="btn btn-danger">Delete field</button>
    </form>
    @endif
    
</div>
@endsection