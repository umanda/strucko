@extends('layouts.master')

@section('meta-description', trans('pages.login.description'))

@section('title', trans('pages.login.title'))

@section('content')
<form method="POST" action="{{ action('Auth\AuthController@postLogin') }}">

    {!! csrf_field() !!}
    
    {!! getLocaleInputField() !!}

    <div class="form-group">
        <label for="email">{{ trans('pages.login.email') }}:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="{{ trans('pages.login.email') }}" class="form-control"
               value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label for="password">{{ trans('pages.login.password') }}:</label>
        <input type="password" id="password" name="password" required="required"
               placeholder="{{ trans('pages.login.password') }}" class="form-control">
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="remember"> {{ trans('pages.login.remember') }}
        </label>
    </div>

    <div class="form-group">
        <button type="submit" id="submit" name="submit" 
                class="btn btn-primary">{{ trans('pages.login.login') }}</button>
                
        <a class="btn btn-link" href="{{ resolveUrlAsUrl('/password/email') }}">
            {{ trans('pages.login.forgotpassword') }}</a>
    </div>

</form>
@endsection