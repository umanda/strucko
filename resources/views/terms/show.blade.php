@extends('layouts.master')

@section('meta-description', 'All about ' . $term->term)

@section('title', $term->term . ' - definitions and translations')

@section('content')

<h3>{{ $term->term }} {!! $term->status->id < 1000 ? status_warning($term->status->status) : '' !!}</h3>
@include('votes.form_up')
@include('votes.form_down')
<p> 
    {{ $term->language->ref_name }}, 
    {{ $term->scientificField->scientific_field }}, 
    {{ $term->partOfSpeech->part_of_speech }},
    {{ $term->menu_letter }}
</p>
<p>Votes: {{ $term->votes_sum }}</p>
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

<h4>Sugessted by user:</h4>
<p>{{ $term->user->name }}</p>
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