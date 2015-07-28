@extends('layouts.master')

@section('meta-description', 'Password reset form')

@section('title', 'Password reset form')

@section('content')
<form method="POST" action="{{ action('Auth\PasswordController@postReset') }}">

    {!! csrf_field() !!}
    
    <input type="hidden" name="token" value="{{ $token }}">
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="Email" class="form-control"
               value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required="required"
               placeholder="Password" class="form-control">
    </div>
    
    <div class="form-group">
        <label for="password_confirmation">Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" 
               required="required" placeholder="Password confirmation" class="form-control">
    </div>

    <div class="form-group">
        <button type="submit" id="submit" name="submit" 
                class="btn btn-primary">Reset password</button>
    </div>

</form>
@endsection