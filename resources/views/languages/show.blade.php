@extends('layouts.master')

@section('meta-description', 'About ' . $language->ref_name)

@section('title', $language->ref_name . ' - description')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a class="btn btn-default" href="{{ action('LanguagesController@index')}}">Back to all languages</a>
    </div>
</div>
<div class="col-md-8">

    <h2>{{ $language->ref_name }}</h2>

    <h3>ID: {{ $language->id }}</h3>
    <h3>Locale: {{ $language->locale }}</h3>
    <h3>Part 2b: {{ $language->part2b }}</h3>
    <h3>Part 2t: {{ $language->part2t }}</h3>
    <h3>Part 1: {{ $language->part1 }}</h3>
    <h3>Scope: {{ $language->scope }}</h3>
    <p>I(ndividual), M(acrolanguage), S(pecial)</p>
    <h3>Type: {{ $language->type }}</h3>
    <p>A(ncient), C(onstructed), E(xtinct), H(istorical), L(iving), S(pecial)</p>
    <h3>Comment: {{ $language->comment }}</h3>
    <h3>Active: {{ $language->active ? 'Yes' : 'No' }} </h3>

    @if ( Auth::check() && ! (Auth::user()->role_id < 1000) )
        <form method="POST" action="{{ action('LanguagesController@destroy', $language->id) }}">
            {!! csrf_field() !!}
            <input type="hidden" name="_method" value="DELETE">
            <a class="btn btn-default" href="{{ action('LanguagesController@edit', $language->id) }}">Edit language</a>
            <button type="submit" class="btn btn-danger">Delete language</button>
        </form>
    @endif
    
</div>
@endsection