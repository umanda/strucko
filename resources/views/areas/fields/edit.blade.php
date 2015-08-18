@extends('layouts.master')

@section('meta-description', 'Edit ' . $field->scientific_field)

@section('title', 'Edit ' . $field->scientific_field)

@section('content')

<h2>Edit {{ $field->scientific_field }}</h2>

<form method="POST" action="{{ action('ScientificFieldsController@update', [$field->scientific_area_id, $field->id]) }}">
    
    <input type="hidden" name="_method" value="PATCH">
    
    @include('areas.fields.form')
    

<button type="submit" class="btn btn-primary">Save</button>

</form>
    
@endsection