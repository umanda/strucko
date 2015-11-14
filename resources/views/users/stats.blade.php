@extends('layouts.master')

@section('meta-description', 'User home page - the Expert Dictionary')

@section('title', 'User home page - the Expert Dictionary')

@section('content')
    <h2>Your stats</h2>
    <p>
        Hello {{ Request::user()->name }}, 
    </p>
    <p>
        Your current role is <i>{{ Request::user()->role->role }}</i> and you can 
        suggest up to {{ Request::user()->role->spam_threshold }} terms and
        definitions. When your suggestions get approved, you can make new 
        ones. We may upgrade roles for users with many approved
        suggestions.
    </p>
    <p>
        Approved terms: {{ $stats['terms']['approved'] }} 
        <br>
        Approved definitions: {{ $stats['definitions']['approved'] }}
    </p>
    <p>
        Suggested terms: {{ $stats['terms']['suggested'] }} 
        <br>
        Suggested definitions: {{ $stats['definitions']['suggested'] }}
    </p>
    <p>
        Rejected terms: {{ $stats['terms']['rejected'] }} 
        <br>
        Rejected definitions: {{ $stats['definitions']['rejected'] }}
    </p>
@endsection