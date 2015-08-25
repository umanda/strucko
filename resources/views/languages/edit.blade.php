@extends('layouts.master')

@section('meta-description', 'Edit ' . $language->ref_name)

@section('title', 'Edit ' . $language->ref_name)

@section('content')

<h2>Edit {{ $language->ref_name }}</h2>

<form method="POST" action="{{ action('LanguagesController@update', $language->id) }}">
    
    <input type="hidden" name="_method" value="PATCH">
    
    @include('languages.form')
    

<button type="submit" class="btn btn-primary">Save</button>
<a class="btn btn-default" href="{{ url('/languages', $language->id) }}">Cancel</a>

</form>
    
@endsection