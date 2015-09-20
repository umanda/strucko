@extends('layouts.master')

@section('meta-description', 'All about ' . $term->term)

@section('title', $term->term . ' - definitions and translations')

@section('content')

<h3>{{ $term->term }}</h3>
<p> 
    {{ $term->language->ref_name }}, 
    {{ $term->scientificField->scientific_field }}, 
    {{ $term->partOfSpeech->part_of_speech }},
    {{ $term->status->status }}, 
    {{ $term->menu_letter }}
</p>
<p>Votes: {{ $term->votes_sum }}</p>
@if(isset($synonyms))
<h4>Synonyms (ID is {{ $term->concept_id }}):</h4>
<ul>
    @foreach($synonyms as $synonym)
        <li> {{ $synonym->term }} {{ $synonym->votes_sum }} {{ $synonym->status->id < 1000 ? $synonym->status->status : '' }}</li>
    @endforeach
</ul>
@endif

<i> TODO merge suggestions </i>

<h4>Sugessted by user:</h4>
<p>{{ $term->user->name }}</p>
<h4>Translations:</h4>
    <i>TODO</i>
<h4>Definitions:</h4>
<ul>
@foreach ($term->concept->definitions as $definition)
    <li>{{ $definition->definition }}</li>
@endforeach
</ul>

@if (Auth::check() && ! (Auth::user()->role_id < 1000))

    <a class="btn btn-default" href="{{ action('TermsController@edit', ['slug' =>
                $term->slug]) }}">Edit</a>
    
    @include('terms.suggestions')
                
@elseif(Auth::check())

    @include('terms.suggestions')

@endif

@endsection