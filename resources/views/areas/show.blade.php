@extends('layouts.master')

@section('meta-description', 'About ' . $area->scientific_area)

@section('title', $area->scientific_area . ' - description')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-default" href="{{ action('ScientificAreasController@index')}}">Back to all areas</a>
    </div>
</div>
<div class="col-md-4">

    <h2>{{ $area->scientific_area }}</h2>

    <h3>Mark: {{ $area->mark }}</h3>
    <h3>Active: {{ $area->active ? 'Yes' : 'No' }} </h3>

    <h3>Description:</h3>
    <p>{{ $area->description ?: 'No description yet...' }}</p>

    @if ( Auth::check() && ! (Auth::user()->role_id < 1000) )
        <form method="POST" action="{{ action('ScientificAreasController@destroy', $area->id) }}">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="DELETE">
            <a class="btn btn-default" href="{{ action('ScientificAreasController@edit', [$area->id]) }}">Edit area</a>
            <button type="submit" class="btn btn-danger">Delete area</button>
        </form>
    @endif
    
    
    
</div>
<div class="col-md-4">
    <h3>Fields in this area:</h3>
    @if ( Auth::check() && ! (Auth::user()->role_id < 1000) )
    <a class="btn btn-default" href="{{ action('ScientificFieldsController@create', $area->id) }}">Create new field</a>
    @endif
    @foreach ($area->scientificFields->sortBy('mark') as $field)
    
        <h4><a href="{{ route('scientific-areas.scientific-fields.show', [$area->id, $field->id]) }}">
                {{ $field->scientific_field . ' - ' . $field->mark }}</a>
        <span class="label label-warning">{{ $field->active ? '' : 'Inactive' }}</span>
        </h4>
    
    @endforeach
</div>
@endsection