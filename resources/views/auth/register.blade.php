@extends('layouts.master')

@section('meta-description', 'Register on Strucko - The Expert Dictionary')

@section('title', 'Registration form')

@section('content')
<form method="POST" action="{{ action('Auth\AuthController@postRegister') }}">

    {!! csrf_field() !!}

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" maxlength="255" required="required"
               placeholder="Name" class="form-control"
               value="{{ old('name') }}">
    </div>
    
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
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" 
               required="required" placeholder="Confirm Password" class="form-control">
    </div>
    
    <div class="form-group">
        <button type="submit" id="submit" name="submit" 
                class="btn btn-primary">Register</button>
    </div>

</form>
@endsection
