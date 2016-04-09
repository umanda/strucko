@extends('layouts.master')

@section('meta-description', trans('pages.about.description'))

@section('title', trans('pages.about.title'))

@section('content')
<article>
    <h2>{{ trans('pages.about.header') }}</h2>
    
    {!! trans('pages.about.about') !!}
        
</article>
@endsection


