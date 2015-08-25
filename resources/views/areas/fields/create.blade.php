@extends('layouts.master')

@section('meta-description', 'Create a new scientific field')

@section('title', 'Create scientific field')

@section('content')

<h2>Create scientific field</h2>

<form method="POST" action="{{ action('ScientificFieldsController@store') }}">
    
    @include('areas.fields.form')
    

    <input type="submit" id="submit" name="submit" value="Create field"
        class="btn btn-primary">
    
    <a class="btn btn-default" href="{{ url('scientific-areas', [$currentArea]) }}">
        Cancel
    </a>
</form>
    
    
@endsection