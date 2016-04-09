@extends('layouts.master')

@section('meta-description', trans('users.index.description'))

@section('title', trans('users.index.title'))

@section('content')
    <h2>{{ trans('users.index.header') }}</h2>
    <p>
        {{ trans('users.index.hello') }} {{ Request::user()->name }}, 
    </p>
    <p>
        {!! trans('users.index.role', [
            'role' => Request::user()->role->role,
            'spam_threshold' => Request::user()->role->spam_threshold
                ]) !!}
    </p>
    <p>
        {{ trans('users.index.termsapproved', [
                    'termsapproved' => $stats['terms']['approved'] ])  }}
        <br>
        {{ trans('users.index.definitionsapproved', [
                    'definitionsapproved' => $stats['definitions']['approved'] ]) }}
    </p>
    <p>
        {{ trans('users.index.termssuggested', [
                    'termssuggested' => $stats['terms']['suggested'] ]) }}
        <br>
        {{ trans('users.index.definitionssuggested', [
                    'definitionssuggested' => $stats['definitions']['suggested'] ]) }}
    </p>
    <p>
        {{ trans('users.index.termsrejected', [
                    'termsrejected' => $stats['terms']['rejected'] ]) }}
        <br>
        {{ trans('users.index.definitionsrejected', [
                    'definitionsrejected' => $stats['definitions']['rejected'] ]) }}
    </p>
@endsection