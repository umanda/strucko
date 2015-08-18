@extends('layouts.master')

@section('meta-description', 'Scientific Areas used in the Strucko Dictionary.')

@section('title', 'Scientific Areas')

@section('content')
    @foreach($areas as $area)
    <h2><a href="{{ route('scientific-areas.show', $area->id) }}">{{ $area->scientific_area . ' - ' . $area->mark }}</a>
        {{ $area->active ? '' : 'Inactive' }}
    </h2>
    @endforeach
@endsection