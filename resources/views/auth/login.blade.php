@extends('layouts.master')

@section('meta-description', 'Login form for the Strucko - The Expert Dictionary')

@section('title', 'Login form')

@section('content')
<form method="POST" action="{{ action('Auth\AuthController@postLogin') }}">

    {!! csrf_field() !!}

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="255" required="required"
               placeholder="Email" class="form-control">
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required="required"
               placeholder="Password" class="form-control">
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="remember"> Remember Me
        </label>
    </div>

    <div class="form-group">
        <button type="submit" id="submit" name="submit" 
                class="btn btn-primary">Login</button>
                
        <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
    </div>

</form>
@endsection