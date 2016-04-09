@extends('layouts.master')

@section('meta-description', trans('terms.edit.description'))

@section('title', trans('terms.edit.title'))

@section('content')
<h2>{{ trans('terms.edit.header') }} <em>{!! $term->term !!}</em></h2>
<form class="form-inline" method="POST" action="{{ action('TermsController@updateStatus', ['slug' => $term->slug]) }}">
    <input type="hidden" name="_method" value="PATCH">
    {!! csrf_field() !!}
    {!! getLocaleInputField() !!}
    <div class="form-group">
        <label for="status_id">{{ trans('terms.edit.status') }}:</label>
        {!! Form::select('status_id', $statuses,
        isset($term) ? $term->status_id : old('status_id'), 
        ['id' => 'status_id', 'required' => 'required', 'class' => 'form-control']) !!}

    </div>
    <button type="submit" class="btn btn-default">{{ trans('terms.edit.setstatus') }}</button>
</form>

<form method="POST" action="{{ action('TermsController@update', ['slug' => $term->slug]) }}">

    <input type="hidden" name="_method" value="PATCH">

    @include('terms.form')

    <div class="form-group">
        <input type="submit" id="submit" name="submit" 
               value="{{ trans('terms.edit.saveterm') }}"
               class="btn btn-primary">
        <a class="btn btn-default" 
           href="{{ resolveUrlAsAction('TermsController@show', ['slug' => $term->slug]) }}">
            {{ trans('terms.edit.cancel') }}</a>
    </div>

</form>
<hr>

@endsection