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
    <div class="col-xs-2 col-lg-1 text-center vertical-center">
        <div class="center-block">
            @include('votes.form_term')
        </div>
    </div>
    <div class="col-xs-10 col-lg-7 vertical-center">
        <h1>
            {{ $term->term }} 
            <small>
                {{ $term->partOfSpeech->part_of_speech }}
            </small>
            {!! $term->status->id < 1000 ? status_warning($term->status->status) : '' !!}
        </h1>
        <small>in <strong>{{ $term->language->ref_name }}, {{ $term->scientificField->scientific_field }};</strong></small>
        <small>by {{ $term->user->name }}</small>
    </div>
    <div class="col-sm-12 col-lg-4 margin-top-15 vertical-center">
        {{-- The Translate to form for selecting translation language --}}
        @include('terms.translations.translate_to')
    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12">
        <table class="table">
            <thead>
                <th class="col-xs-10">Definitions</th>
                <th class="col-xs-1"></th>
                <th class="col-xs-1"></th>
            </thead>
            <tbody>
                @foreach ($term->concept->definitions as $definition)
                    <tr>
                        <td class="vertical-center-cell">
                            {{ $definition->definition }} {{ $definition->status->id < 1000 ? $definition->status->status : '' }}  
                        </td>
                        <td>
                            TODO
                        </td>
                        <td>
                            TODO
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <table class="table">
            <thead>
                <th class="col-xs-10">Synonyms</th>
                <th class="col-xs-1"></th>
                <th class="col-xs-1"></th>
            </thead>
            <tbody>
                @if( ! $synonyms->isEmpty())
                    @foreach($synonyms as $synonym)
                    <tr>
                        <td class="vertical-center-cell">
                            {{ $synonym->term }} {{ $synonym->votes_sum }} {{ $synonym->status->id < 1000 ? $synonym->status->status : '' }}  
                        </td>
                        <td>
                            TODO
                            <form action="{{ action('TermVotesController@voteUp', [$synonym->slug]) }}" method="POST">
                                @include('votes.form_up_naked')
                            </form>
                        </td>
                        <td>
                            <form action="{{ action('TermVotesController@voteDown', [$synonym->slug]) }}" method="POST">
                                @include('votes.form_down_naked')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        <table class="table table-condensed">
            <thead>
                <th class="col-xs-10">Translations</th>
                <th class="col-xs-1"></th>
                <th class="col-xs-1"></th>
            </thead>
            <tbody>
                @if (isset($translations))
                @unless($translations->isEmpty())
                    @foreach($translations as $translation)
                    <tr>
                        <td class="vertical-center-cell">
                            {{ $translation->term }} {{ $translation->votes_sum }}
                            {{ $translation->status->id < 1000 ? $translation->status->status : '' }}  
                        </td>
                        <td>
                            TODO
                            <form action="{{ action('TermVotesController@voteUp', [$synonym->slug]) }}" method="POST">
                                @include('votes.form_up_naked')
                            </form>
                        </td>
                        <td>
                            <form action="{{ action('TermVotesController@voteDown', [$synonym->slug]) }}" method="POST">
                                @include('votes.form_down_naked')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @endunless
                @endif
            </tbody>
        </table>
    </div>
</div>


<big>TODO merge suggestion</big>
@if($term->mergeSuggestions()->exists())
<p>This term is suggested to be merged with these:</p>
@foreach($term->mergeSuggestions as $mergeSuggestion)
@foreach($mergeSuggestion->concept->terms as $term)
{{ $term->term }},
@endforeach
@endforeach
@endif


@if (Auth::check() && ! (Auth::user()->role_id < 1000))

<a class="btn btn-default" href="{{ action('TermsController@edit', ['slug' =>
                $term->slug]) }}">Edit</a>

@include('terms.suggestions')

@elseif(Auth::check())

@include('terms.suggestions')

@endif

@endsection