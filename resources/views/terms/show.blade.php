@extends('layouts.master')

@section('meta-description', 'All about ' . $term->term)

@section('title', $term->term . ' - definitions and translations')

@section('content')

<div class="row">

    <div class="col-xs-12 text-right">
        <a class="btn-link btn-lg" 
           href="{{ action('TermsController@index', [
               'language_id' => $term->language_id,
               'scientific_field_id' => $term->scientific_field_id,
               'menu_letter' => $term->menu_letter,
           ] + Session::get('termShowFilters')) }}">
            {{ $term->language->ref_name }}, 
            {{ $term->scientificField->scientific_field }}
        </a>
    </div>
</div>
<div class="row">
    <div class="col-xs-2 col-lg-1 text-center">
        @include('votes.form_up')
        <span class="vote-lg">{{ $term->votes_sum }}</span>
        @include('votes.form_down')
    </div>
    <div class="col-xs-10 col-lg-7">
        <h1>
            {{ $term->term }} 
            <small>
                {{ $term->partOfSpeech->part_of_speech }}
            </small>
            {!! $term->status->id < 1000 ? status_warning($term->status->status) : '' !!}
        </h1>
         <small>in {{ $term->language->ref_name }}, {{ $term->scientificField->scientific_field }};</small>
       <small>by {{ $term->user->name }}</small>
    </div>
    <div class="col-sm-12 col-lg-4 vcenter">
        <p>TODO translate to form</p>
    </div>
</div>

@if( ! $synonyms->isEmpty())
<h4>Synonyms (ID is {{ $term->concept_id }}):</h4>
<ul>
    @foreach($synonyms as $synonym)
    <li> {{ $synonym->term }} {{ $synonym->votes_sum }} {{ $synonym->status->id < 1000 ? $synonym->status->status : '' }}    
        <form action="{{ action('TermVotesController@voteUp', [$synonym->slug]) }}" method="POST">
            @include('votes.form_up_naked')
        </form>
        <form action="{{ action('TermVotesController@voteDown', [$synonym->slug]) }}" method="POST">
            @include('votes.form_down_naked')
        </form>
    </li>
    @endforeach
</ul>
@endif

@if($term->mergeSuggestions()->exists())
<p>This term is suggested to be merged with these:</p>
@foreach($term->mergeSuggestions as $mergeSuggestion)
@foreach($mergeSuggestion->concept->terms as $term)
{{ $term->term }},
@endforeach
@endforeach
@endif

<h4>Translations:</h4>
@if (isset($translations))
@unless($translations->isEmpty())
<ul>
    @foreach($translations as $translation)
    <li> {{ $translation->term }} {{ $translation->votes_sum }} {{ $translation->status->id < 1000 ? $translation->status->status : '' }}
        <form action="{{ action('TermVotesController@voteUp', [$translation->slug]) }}" method="POST">
            @include('votes.form_up_naked')
        </form>
        <form action="{{ action('TermVotesController@voteDown', [$translation->slug]) }}" method="POST">
            @include('votes.form_down_naked')
        </form>
    </li>
    @endforeach
</ul>
@else
<p>No translations to selected language</p>
@endunless
@endif
<h4>Definitions:</h4>
<ul>
    @foreach ($term->concept->definitions as $definition)
    <li>{{ $definition->definition }} {{ $definition->status->id < 1000 ? $definition->status->status : '' }}</li>
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