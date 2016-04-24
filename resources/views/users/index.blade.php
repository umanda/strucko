@extends('layouts.master')

@section('meta-description', trans('users.index.description'))

@section('title', trans('users.index.title'))

@section('content')
    <h2>{{ trans('users.index.header') }}</h2>
    <ul>
        @foreach ($users as $user)
            <li>{{ $user->name }} - {{ $user->role->role }} - {{ $user->created_at }}</li>
        @endforeach
    </ul>
@endsection