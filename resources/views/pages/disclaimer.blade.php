@extends('layouts.master')

@section('meta-description', trans('pages.disclaimer.description'))

@section('title', trans('pages.disclaimer.title'))

@section('content')
<article>
    <h2>{{ trans('pages.disclaimer.header') }}</h2>
    {!! trans('pages.disclaimer.content') !!}
</article>
@endsection


