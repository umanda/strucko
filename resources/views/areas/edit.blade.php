@extends('layouts.master')

@section('meta-description', 'Edit ' . $area->scientific_area)

@section('title', 'Edit ' . $area->scientific_area)

@section('content')

<h2>Edit {{ $area->scientific_area }}</h2>

<form method="POST" action="{{ action('ScientificAreasController@update', $area->id) }}">
    
    <input type="hidden" name="_method" value="PATCH">
    
    @include('areas.form')
    

<button type="submit" class="btn btn-primary">Save</button>

</form>
    
@endsection