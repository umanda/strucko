@extends('layouts.master')

@section('meta-description', 'Scientific Fields used in the Strucko Dictionary.')

@section('title', 'Scientific Fields')

@section('content')
    @foreach($fields as $field)
    
    <h2><a href="{{ route('scientific-areas.scientific-fields.show', [
        $field->scientificArea->id,
        $field->id
        ]) }}">{{ $field->scientific_field . ' - ' . $field->mark }}</a>
        {{ $field->active ? '' : 'Inactive' }}    
    </h2>
    @endforeach
    <a class="btn btn-default" href="{{ route('scientific-areas.show', [
        $field->scientificArea->id,
        ]) }}"> Back to {{ $field->scientificArea->scientific_area }}</a>
@endsection