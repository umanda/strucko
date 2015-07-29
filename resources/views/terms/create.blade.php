@extends('layouts.master')

@section('meta-description', 'Suggest a new term in the Strucko Expert Dictionary')

@section('title', 'Suggest a new term')

@section('content')

<h2>Suggest a new term</h2>

<form method="POST" action="{{ action('TermsController@store') }}">
    {!! csrf_field() !!}
    
    <div class="form-group">
        <label for="term">Term:</label>
        <input type="text" id="term" name="term" maxlength="255" required="required"
               placeholder="Term" class="form-control"
               value="{{ old('term') }}">
    </div>
    
    <div class="form-group">
        <label for="language_id">Language:</label>
        <select id="language_id" name="language_id" required="required" class="form-control">
            @foreach ($languages as $language)
            <option value="{{ $language->id }}">{{ $language->ref_name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="part_of_speech_id">Part of Speech:</label>
        <select id="part_of_speech_id" name="part_of_speech_id" required="required" class="form-control" >
            @foreach ($partOfSpeeches as $partOfSpeech)
            <option value="{{ $partOfSpeech->id }}">{{ $partOfSpeech->part_of_speech }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="scientific_branch_id">Scientific Branch (category):</label>
        <select id="scientific_branch_id" name="scientific_branch_id" required="required" class="form-control">
            @foreach ($scientificBranches as $scientificBranch)
            <option value="{{ $scientificBranch->id }}">{{ $scientificBranch->scientific_branch }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="abbreviation">Abbreviation (optional):</label>
        <input type="text" id="abbreviation" name="abbreviation" maxlength="255" 
               placeholder="Abbreviation" class="form-control"
               value="{{ old('abbreviation') }}">
    </div>
    
    <div class="form-group">
        <input type="submit" id="submit" name="submit" value="Suggest term"
               class="btn btn-primary">
    </div>
    
    
</form>
    
    
@endsection