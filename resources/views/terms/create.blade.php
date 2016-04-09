@extends('layouts.master')

@section('meta-description', 'Suggest a new term in Strucko The Expert Dictionary')

@section('title', 'Suggest a new term')

@section('content')

<h2>{{ trans('terms.create.suggestnewterm') }}</h2>

<form method="POST" action="{{ action('TermsController@store') }}">
    
    @include('terms.form')
    
    <div class="form-group">
        <input type="submit" id="submit" name="submit" value="{{ trans('terms.create.suggestterm') }}"
               class="btn btn-primary">
    </div>
    
    
</form>
    
    
@endsection