@extends('layouts.master')

@section('meta-description', trans('pages.cookie.description'))

@section('title', trans('pages.cookie.title'))

@section('content')
<article>
    <h2>{{ trans('pages.cookie.header')}}</h2>
    
    {!! trans('pages.cookie.content') !!}
    
</article>
@endsection


