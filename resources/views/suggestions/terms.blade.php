@extends('layouts.master')

@section('meta-description', 'Manage all suggestions')

@section('title', 'Suggestions')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('suggestions.menu')
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="/suggestions/terms" class="form-inline">
                        @include('suggestions.filter')
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        @foreach ($terms as $term)
                        <li>{{ $term->term }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
