@extends('layouts.master')

@section('meta-description', trans('pages.contact.description'))

@section('title', trans('pages.contact.title'))

@section('content')
    <h2>{{ trans('pages.contact.header') }}</h2>
    
    {!! trans('pages.contact.content') !!}
    
@endsection


