@extends('layouts.master')

@section('meta-description', trans('pages.privacy.description'))

@section('title', trans('pages.privacy.title'))

@section('content')
<article>
    <h2>{{ trans('pages.privacy.header') }}</h2>
    {!! trans('pages.privacy.content') !!}
    
</article>
@endsection


