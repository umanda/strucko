@extends('layouts.master')

@section('meta-description', 'Show the specific merge suggestions')

@section('title', 'Merge suggestion')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('suggestions.menu')
    </div>
    <div class="col-md-9">
        <h1>Merge Suggestion</h1>
        <p>Terms suggested to be merged:</p>
        <p>
            
        </p>
    </div>
</div>
    
@endsection