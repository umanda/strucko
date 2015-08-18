@extends('layouts.master')

@section('meta-description', 'Create a new scientific area')

@section('title', 'Create scientific area')

@section('content')

<h2>Create scientific area</h2>

<form method="POST" action="{{ action('ScientificAreasController@store') }}">
    
    @include('areas.form')
    
    <div class="form-group">
        <input type="submit" id="submit" name="submit" value="Suggest area"
               class="btn btn-primary">
    </div>
    
    
</form>
    
    
@endsection