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
    <div class="col-xs-2 text-center vertical-center">
        <div class="center-block">
            @include('votes.form_term')
        </div>
    </div>
    <div class="col-xs-10 vertical-center">
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
</div>

<hr>
<!-- Definitions -->
<div class="row">
    <div class="col-xs-12">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-9">Definitions</th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                @unless($term->concept->definitions->isEmpty())
                    @foreach ($term->concept->definitions as $definition)
                        <tr>
                            <td class="vertical-center-cell">
                                {{ $definition->definition }}
                                {!! $definition->status->id < 1000 ? status_warning($definition->status->status) : '' !!}  
                            </td>
                            {{-- Votes for definition --}}
                            @include('votes.form_definition_table')
                        </tr>
                    @endforeach
                @else
                    <tr><td><span class="text-warning">
                                    No definitions
                                </span></td></tr>
                @endunless
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <!-- Translations -->
    <div class="col-sm-6">
        
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-9">
                        {{-- The Translate to form for selecting translation language --}}
                        @include('terms.translations.translate_to')
                    </th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                {{-- First chek if the translate_to is set, and show appropriate message --}}
                @if(Session::has('termShowFilters'))
                    @if(null == Session::get('termShowFilters.translate_to'))
                    <tr><td><span class="text-warning">Select language to translate to</span></td></tr>
                    @endif
                @endif
                @if (isset($translations))
                    @unless($translations->isEmpty())
                        @foreach($translations as $translation)
                        <tr>
                            <td class="vertical-center-cell">
                                {{ $translation->term }}
                                {!! $translation->status->id < 1000 ? status_warning($translation->status->status) : '' !!}  
                            </td>
                            {{-- Votes for translations --}}
                            @include('votes.form_term_table', ['workingTerm' => $translation])
                            
                        </tr>
                        @endforeach
                    @else
                        {{-- No translations, translations is empty --}}
                        <tr><td><span class="text-warning">
                                    No translations in selected language
                                </span></td></tr>
                    @endunless
                @endif
            </tbody>
        </table>
        
    </div>
    <!-- Synonyms -->
    <div class="col-sm-6">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-9">Synonyms</th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                @if( ! $synonyms->isEmpty())
                    @foreach($synonyms as $synonym)
                    <tr>
                        <td class="vertical-center-cell">
                            {{ $synonym->term }}
                            {!! $synonym->status->id < 1000 ? status_warning($synonym->status->status) : '' !!}  
                        </td>
                        {{-- Votes for translations --}}
                        @include('votes.form_term_table', ['workingTerm' => $synonym])
                    </tr>
                    @endforeach
                @else
                    {{-- No translations, translations is empty --}}
                    <tr><td><span class="text-warning">
                                    No synonyms
                            </span></td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{--Check if merge suggestions exist--}}
@if($term->mergeSuggestions()->exists())
    <table class="table table-condensed">
        <caption>Merge suggestion</caption>
        <thead>
            <tr>
                <th class="col-xs-9">Merge suggestions</th>
                <th class="col-xs-1"></th>
                <th class="col-xs-1"></th>
                <th class="col-xs-1"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($term->mergeSuggestions as $mergeSuggestion)
                <tr>
                    <td class="vertical-center-cell">
                        @foreach($mergeSuggestion->concept->terms as $key => $mergeTerm)
                            @if($mergeTerm->language_id == $term->language_id)
                                {{ $mergeTerm->term }};
                            @endif
                        @endforeach
                    </td>
                    {{-- Votes for translations --}}
                    {{-- @include('votes.form_term_table', ['workingTerm' => $synonym]) --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@endif



@if (Auth::check() && ! (Auth::user()->role_id < 1000))

    <a class="btn btn-default" href="{{ action('TermsController@edit', ['slug' =>
                    $term->slug]) }}">Edit</a>

    @include('terms.suggestions')

@elseif(Auth::check())

    @include('terms.suggestions')

@endif

@endsection