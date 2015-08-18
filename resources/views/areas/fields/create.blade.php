@extends('layouts.master')

@section('meta-description', 'Create a new scientific field')

@section('title', 'Create scientific field')

@section('content')

<h2>Create scientific field</h2>

<form method="POST" action="{{ action('ScientificFieldsController@store') }}">
    
    @include('areas.fields.form')
    
    <div class="form-group">
        <input type="submit" id="submit" name="submit" value="Create field"
               class="btn btn-primary">
    </div>
    
    
</form>
    
    
@endsection