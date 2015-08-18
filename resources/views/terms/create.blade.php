@extends('layouts.master')

@section('meta-description', 'Suggest a new term in the Strucko Expert Dictionary')

@section('title', 'Suggest a new term')

@section('content')

<h2>Suggest a new term</h2>

<form method="POST" action="{{ action('TermsController@store') }}">
    
    @include('terms.form')
    
    <div class="form-group">
        <input type="submit" id="submit" name="submit" value="Suggest term"
               class="btn btn-primary">
    </div>
    
    
</form>
    
    
@endsection