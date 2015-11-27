@extends('layouts.master')

@section('meta-description', $showMeta['description'])

@section('title', $showMeta['title'])

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
            <span lang="{{ $term->language->part1 }}">{{ $term->term }}</span> 
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
                    <th class="col-xs-1 text-center">Votes</th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                @unless($term->definitions->isEmpty())
                    @foreach ($term->definitions as $definition)
                        <tr>
                            <td class="vertical-center-cell">
                                <span lang="{{ $definition->language->part1 }}">{{ $definition->definition }}</span>
                                {!! $definition->status->id < 1000 ? status_warning($definition->status->status) : '' !!}
                                @if($definition->source)
                                    @if($definition->link)
                                        <br>
                                        <small>source: <a href="{{ $definition->link }}" target="_blank">
                                                {{ $definition->source }}</a></small>
                                    @else
                                        <br>
                                        <small>source: {{ $definition->source }}</small>
                                    @endif
                                @endif
                                
                                <br><small>by <em>{{ $definition->user->name }}</em></small>
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
                    <th class="col-xs-1 text-center">Votes</th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="4">
                    {{-- If the translate_to is set, show link to terms in translated language --}}
                    @if(Session::has('termShowFilters'))
                        @unless(null == Session::get('termShowFilters.translate_to'))
                        <a class="btn-link"
                           href="{{ action('TermsController@index', [
                               'language_id' => Session::get('termShowFilters.translate_to'),
                               'scientific_field_id' => $term->scientific_field_id,
                               'translate_to' => $term->language_id,
                           ])}}">All terms in selected language</a>
                        @endunless
                    @endif
                    </td>
                </tr>
            </tfoot>
            <tbody>
                {{-- First check if the translate_to is set and show appropriate message --}}
                @if(Session::has('termShowFilters'))
                    @if(null == Session::get('termShowFilters.translate_to'))
                    <tr><td><span class="text-warning">Select language to translate to</span></td></tr>
                    @endif
                @endif
                @if ($term->relationLoaded('translations'))
                    @unless($term->translations->isEmpty())
                        @foreach($term->translations as $translation)
                        <tr>
                            <td class="vertical-center-cell">
                                {{--Depending on the translate_to query,
                                show the appropriate link with translate_to--}}
                                @if(Session::has('termShowFilters'))
                                    @if(null !== Session::get('termShowFilters.translate_to'))
                                    <a lang="{{ $translation->translation->language->part1 }}"
                                       href="{{ action('TermsController@show', [
                                            'slug' => $translation->translation->slug,
                                            'translate_to' => $term->language_id
                                        ])}}">
                                            {{ $translation->translation->term }}</a>
                                    @endif
                                @else
                                {{--I think this part is no longer necesary--}}
                                <a lang="{{ $translation->translation->language->part1 }}"
                                    href="{{ action('TermsController@show', $translation->translation->slug) }}">
                                    {{ $translation->translation->term }}</a>
                                @endif
                                {!! $translation->status->id < 1000 ? status_warning($translation->status->status) : '' !!}  
                                <br><small>by <i>{{ $translation->user->name }}</i></small>
                            </td>
                            {{-- Votes for translations --}}
                            @include('votes.form_translation_table')
                            
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
                    <th class="col-xs-1 text-center">Votes</th>
                    <th class="col-xs-1"></th>
                </tr>
            </thead>
            <tbody>
                @if( ! $term->synonyms->isEmpty())
                    @foreach($term->synonyms as $synonym)
                    <tr>
                        <td class="vertical-center-cell">
                            {{--Depending on the translate_to query,
                                show the appropriate link with translate_to--}}
                            @if(Session::has('termShowFilters')
                                && (null !== Session::get('termShowFilters.translate_to')))
                                <a lang="{{ $synonym->synonym->language->part1 }}"
                                    href="{{ action('TermsController@show', [
                                    'slug' => $synonym->synonym->slug,
                                    'translate_to' => Session::get('termShowFilters.translate_to')
                                ])}}">
                                    {{ $synonym->synonym->term }}</a>
                            @else
                            <a lang="{{ $synonym->synonym->language->part1 }}"
                                href="{{ action('TermsController@show', $synonym->synonym->slug) }}">
                                {{ $synonym->synonym->term }}</a>
                            @endif
                            {!! $synonym->status->id < 1000 ? status_warning($synonym->status->status) : '' !!}
                            <br><small>by <i>{{ $synonym->user->name }}</i></small>
                        </td>
                        {{-- Votes for synonyms --}}
                        @include('votes.form_synonym_table')
                    </tr>
                    @endforeach
                @else
                    {{-- No synonyms --}}
                    <tr><td><span class="text-warning">
                                    No synonyms
                            </span></td>
                            <td></td><td></td><td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@if (Auth::check() && ! (Auth::user()->role_id < 1000))
    <a class="btn btn-default" href="{{ action('TermsController@edit', ['slug' =>
                    $term->slug]) }}">Edit</a>
    @include('terms.suggestions')
    @include('shared.disqus_show_term_user')
@elseif(Auth::check())
    @include('terms.suggestions')
    @include('shared.disqus_show_term_user')    
@endif
@if (Auth::guest())
    @include('shared.disqus_show_term_guest')
@endif

@endsection