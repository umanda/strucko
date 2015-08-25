@extends('layouts.master')

@section('meta-description', 'Scientific Areas used in the Strucko Dictionary.')

@section('title', 'Scientific Areas')

@section('content')
    <h2>List of scientific areas included in Strucko</h2>
    
    @if ( Auth::check() && ! (Auth::user()->role_id < 1000) )
    <a class="btn btn-default" href="{{ action('ScientificAreasController@create') }}">Create new area</a>
    @endif
    
    @foreach($areas as $area)
    <h3><a href="{{ route('scientific-areas.show', $area->id) }}">{{ $area->scientific_area . ' - ' . $area->mark }}</a>
        <span class="label label-warning">{{ $area->active ? '' : 'Inactive' }}</span>
    </h3>
    @endforeach
    
    
@endsection