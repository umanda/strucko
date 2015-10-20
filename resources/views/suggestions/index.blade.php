@extends('layouts.master')

@section('meta-description', 'Manage all suggestions')

@section('title', 'Suggestions')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('suggestions.menu')
    </div>
    <div class="col-md-9">
        <div class="jumbotron">
           <p>
               Here you can browse all terms and definitions that are not yet approved.
               Feel free to look around and be sure to vote. Log in first...
           </p>
        </div>
    </div>
</div>
    
@endsection