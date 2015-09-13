@extends('layouts.master')

@section('meta-description', 'Manage all suggestions')

@section('title', 'Suggestions')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('suggestions.menu')
    </div>
    <div class="col-md-9">
        <p>Please, feel free to look at all suggestions and vote!</p>
    </div>
</div>
    
@endsection