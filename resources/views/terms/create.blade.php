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
        <label for="language">Language:</label>
        <select id="language" name="language" required="required" class="form-control">
            @foreach ($languages as $language)
            <option value="{{ $language->id }}">{{ $language->ref_name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="part-of-speech">Part of Speech:</label>
        <select id="part-of-speech" name="part-of-speech" required="required" class="form-control" >
            @foreach ($partOfSpeeches as $partOfSpeech)
            <option value="{{ $partOfSpeech->id }}">{{ $partOfSpeech->part_of_speech }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label for="scientific-branch">Scientific Branch (category):</label>
        <select id="scientific-branch" name="scientific-branch" required="required" class="form-control">
            @foreach ($scientificBranches as $scientificBranch)
            <option value="{{ $scientificBranch->id }}">{{ $scientificBranch->scientific_branch }}</option>
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