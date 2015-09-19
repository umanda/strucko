@extends('layouts.master')

@section('meta-description', 'Edit the term')

@section('title', 'Edit the term')

@section('content')
<h2> Edit {!! $term->term !!}</h2>
<form class="form-inline" method="POST" action="{{ action('TermsController@updateStatus', ['slug' => $term->slug]) }}">
    <input type="hidden" name="_method" value="PATCH">
    {!! csrf_field() !!}
    <div class="form-group">
        <label for="status_id">Status:</label>
        {!! Form::select('status_id', $statuses,
        isset($term) ? $term->status_id : old('status_id'), 
        ['id' => 'status_id', 'required' => 'required', 'class' => 'form-control']) !!}

    </div>
    <button type="submit" class="btn btn-default">Set status</button>
</form>

<form method="POST" action="{{ action('TermsController@update', ['slug' => $term->slug]) }}">

    <input type="hidden" name="_method" value="PATCH">

    @include('terms.form')

    <div class="form-group">
        <input type="submit" id="submit" name="submit" value="Save term"
               class="btn btn-primary">
        <a class="btn btn-default" href="{{ action('TermsController@show', ['slug' => $term->slug]) }}">Cancel</a>
    </div>

</form>
<hr>

@endsection