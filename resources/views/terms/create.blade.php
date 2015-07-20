@extends('layouts.master')

@section('meta-description', 'Suggest a new term in the Strucko Expert Dictionary')

@section('title', 'Suggest a new term')

@section('content')
<form method="POST" action="{{ action('TermsController@create') }}">
    {!! csrf_field() !!}
    
    <div class="form-group">
        <label for="term">Term:</label>
        <input type="text" id="term" name="term" maxlength="255" required="required"
               placeholder="Term" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="part-of-speech">Part of Speech:</label>
        <select class="form-control">
            @foreach ($partOfSpeeches as $partOfSpeech)
            <option value="{{ $partOfSpeech->id }}">{{ $partOfSpeech->part_of_speech }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="abbreviation">Abbreviation (optional):</label>
        <input type="text" id="abbreviation" name="abbreviation" maxlength="255" 
               placeholder="Abbreviation" class="form-control">
    </div>
</form>
    
    
@endsection