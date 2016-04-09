@extends('layouts.master')

@section('meta-description', trans('pages.password.description'))

@section('title', trans('pages.password.title'))

@section('content')
<form method="POST" action="{{ action('Auth\PasswordController@postEmail') }}">
    
    {!! csrf_field() !!}
    
    {!! getLocaleInputField() !!}
    
    <div class="form-group">
        <label for="email">{{ trans('pages.password.email') }}:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="{{ trans('pages.password.email') }}" class="form-control"
               value="{{ old('email') }}">
    </div>
    
    <button type="submit" class="btn btn-primary">
	{{ trans('pages.password.send') }}
    </button>
    
</form>
    
    
@endsection