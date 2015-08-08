@extends('layouts.master')

@section('meta-description', 'Edit the term')

@section('title', 'Edit the term')

@section('content')
    <h2> Edit {!! $term->term !!}</h2>
    
    <form method="POST" action="{{ action('TermsController@update', ['slugUnique' => $term->slug_unique]) }}">
        
        <input type="hidden" name="_method" value="PATCH">
        
        @include('terms.form')

        <div class="form-group">
            <input type="submit" id="submit" name="submit" value="Save term"
                   class="btn btn-primary">
        </div>


    </form>
@endsection