@extends('layouts.master')

@section('meta-description', 'Reset your password form')

@section('title', 'Reset your password')

@section('content')
<form method="POST" action="{{ action('Auth\PasswordController@postEmail') }}">
    
    {!! csrf_field() !!}
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="Email" class="form-control"
               value="{{ old('email') }}">
    </div>
    
    <button type="submit" class="btn btn-primary">
	Send Password Reset Link
    </button>
    
</form>
    
    
@endsection