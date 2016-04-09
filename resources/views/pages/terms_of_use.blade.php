@extends('layouts.master')

@section('meta-description', trans('pages.terms.description'))

@section('title', trans('pages.terms.title'))

@section('content')
<article>
    <h2>{{ trans('pages.terms.header') }}</h2>
    
    {!! trans('pages.terms.content') !!}
    


</article>
@endsection


