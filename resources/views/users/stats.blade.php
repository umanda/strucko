@extends('layouts.master')

@section('meta-description', trans('users.stats.description'))

@section('title', trans('users.stats.title'))

@section('content')
    <h2>{{ trans('users.stats.header') }}</h2>
    <p>
        {{ trans('users.stats.hello') }} {{ Request::user()->name }}, 
    </p>
    <p>
        {!! trans('users.stats.role', [
            'role' => Request::user()->role->role,
            'spam_threshold' => Request::user()->role->spam_threshold
                ]) !!}
    </p>
    <p>
        {{ trans('users.stats.termsapproved', [
                    'termsapproved' => $stats['terms']['approved'] ])  }}
        <br>
        {{ trans('users.stats.definitionsapproved', [
                    'definitionsapproved' => $stats['definitions']['approved'] ]) }}
    </p>
    <p>
        {{ trans('users.stats.termssuggested', [
                    'termssuggested' => $stats['terms']['suggested'] ]) }}
        <br>
        {{ trans('users.stats.definitionssuggested', [
                    'definitionssuggested' => $stats['definitions']['suggested'] ]) }}
    </p>
    <p>
        {{ trans('users.stats.termsrejected', [
                    'termsrejected' => $stats['terms']['rejected'] ]) }}
        <br>
        {{ trans('users.stats.definitionsrejected', [
                    'definitionsrejected' => $stats['definitions']['rejected'] ]) }}
    </p>
@endsection