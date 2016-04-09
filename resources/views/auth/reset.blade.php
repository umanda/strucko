@extends('layouts.master')

@section('meta-description', trans('pages.reset.description'))

@section('title', trans('pages.reset.title'))

@section('content')
<form method="POST" action="{{ action('Auth\PasswordController@postReset') }}">

    {!! csrf_field() !!}
    
    {!! getLocaleInputField() !!}
    
    <input type="hidden" name="token" value="{{ $token }}">
    
    <div class="form-group">
        <label for="email">{{ trans('pages.forms.email') }}:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="{{ trans('pages.forms.email') }}" class="form-control"
               value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label for="password">{{ trans('pages.forms.password') }}</label>
        <input type="password" id="password" name="password" required="required"
               placeholder="{{ trans('pages.forms.password') }}" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="password_confirmation">{{ trans('pages.forms.passwordconfirm') }}</label>
        <input type="password" id="password_confirmation" name="password_confirmation" 
               required="required" placeholder="{{ trans('pages.forms.passwordconfirm') }}" class="form-control">
    </div>

    <div class="form-group">
        <button type="submit" id="submit" name="submit" 
                class="btn btn-primary">
        {{ trans('pages.reset.reset') }}
        </button>
    </div>

</form>
@endsection