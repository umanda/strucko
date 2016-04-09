@extends('layouts.master')

@section('meta-description', $indexMeta['description'])

@section('title', $indexMeta['title'])

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @if (isset($allFilters['language_id'])
        && isset($allFilters['scientific_field_id']))
        <div class="row">
            <div class="col-xs-10 vertical-center">
                <h2 class="text-left" style="padding: 0px 10px 15px 0px">
                    {{ trans('languages.' . str_replace(' ', '_', $language))  }}
                    {{ isset($allFilters['translate_to']) ? trans('terms.index.to') . ' '
                                . trans('languages.' . $translateToLanguage) : '' }}
                </h2>
            </div>
            <div class="col-xs-2 text-center vertical-center">
                @if (isset($allFilters['translate_to']))
                <a href="{{ resolveUrlAsAction('TermsController@index', [
                    'language_id' => $allFilters['translate_to'],
                    'scientific_field_id' => $allFilters['scientific_field_id'],
                    'translate_to' => $allFilters['language_id']
                    ]) }}" class="btn btn-lg lang-switcher" title="{{ trans('terms.index.switchlanguage')  }}">
                    <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
                </a>
                @endif
            </div>
        </div>
        @endif
        
        
    </div>
</div>
@if(isset($menuLetters) && ! ($menuLetters->isEmpty()))
<div class="row">
    <div class="col-sm-7">
        @include('layouts.menu')
    </div>
    <div class="col-sm-5">
        @include('layouts.search')
    </div>
</div>
@elseif (isset($allFilters['language_id']) && isset($allFilters['scientific_field_id']))
<div class="row">
    <div class="col-sm-12">
        <p class="btn-lg btn-info">{{ trans('terms.index.noterms') }}</p>
        <p class="text-center">
            <a class="btn btn-success" href="{{ resolveUrlAsUrl('/terms/create') }}">
                {{ trans('terms.index.newterm') }}</a>
        </p>
    </div>
</div>
@else
<div class="row">
    <div class="col-xs-12">
        <h2>{{ trans('terms.index.selectlanguage') }}</h2>
        <br>
        <a class="btn-lg btn-info" role="button"
            href="{{ resolveUrlAsUrl('/terms', [
                'language_id' => 'eng',
                'scientific_field_id' => '19',
                'translate_to' => 'hrv'
            ]) }}">{{ trans('languages.English') }}</a>
        <a class="btn-lg btn-info" role="button"
           href="{{ resolveUrlAsUrl('/terms', [
                'language_id' => 'hrv',
                'scientific_field_id' => '19',
                'translate_to' => 'eng'
            ]) }}">{{ trans('languages.Croatian') }}</a>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        @if(isset($terms) && !($terms->isEmpty()))
        <h3>
            {{ trans('terms.index.results') }}
            <i>
                {{ isset($allFilters['menu_letter']) ? $menuLetter : '' }}
                {{ isset($allFilters['search']) ? $search : '' }}
            </i>
        </h3>
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th class="col-xs-6 vertical-center-cell">
                        {{ trans('languages.' . str_replace(' ', '_', $language)) }}
                    </th>
                    <th class="col-xs-6">
                        {{ trans('languages.' . str_replace(' ', '_', $translateToLanguage)) }}
                        {{-- Not used with only two languages: --}}
                        {{-- @include('terms.translate_to_2') --}}
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($terms as $term)
            <tr>
                <td class="vertical-center-cell">
                    @if (isset($allFilters['translate_to']))
                        <small>
                            {{ trans('partofspeeches.' 
                            . str_replace(' ', '_', $term->partOfSpeech->part_of_speech)) }}
                        </small>
                        <br>
                        <a class="btn-link btn-lg" lang="{{ $term->language->part1 }}"
                           href="{{ resolveUrlAsAction('TermsController@show', ['slug' =>
                            $term->slug, 'translate_to' => $allFilters['translate_to'] ]) }}">
                        {{ $term->term }}</a>
                        {!! $term->status->id < 1000 ? 
                        status_warning(trans('statuses.' . str_replace(' ', '_', $term->status->status))) : '' !!}
                    @else
                        <small>{{ trans('partofspeeches.' 
                            . str_replace(' ', '_', $term->partOfSpeech->part_of_speech)) }}</small><br>
                        <a class="btn-link btn-lg" 
                           href="{{ resolveUrlAsAction('TermsController@show', 
                                    ['slug' => $term->slug]) }}">{{ $term->term }}</a>
                        {!! $term->status->id < 1000 ? 
                        status_warning(trans('statuses.' . str_replace(' ', '_', $term->status->status))) : '' !!}
                    @endif
                </td>
                @if (isset($allFilters['translate_to']))
                    <td class="vertical-center-cell">
                        @unless ($term->translations->isEmpty())
                        
                            @foreach ($term->translations as $key => $translation)
                                @if (is_last($term->translations, $key))
                                <span lang="{{ $translation->translation->language->part1 }}">
                                    {{ $translation->translation->term }}
                                </span>
                                {!! $translation->status->id < 1000 ? 
                                    status_warning(trans('statuses.' . str_replace(' ', '_', $translation->status->status))) : '' !!}
                                @else
                                <span lang="{{ $translation->translation->language->part1 }}">
                                    {{ $translation->translation->term }}{!! $translation->status->id < 1000 ? '' : ',' !!}
                                </span>
                                {!! $translation->status->id < 1000 ? 
                                    status_warning(trans('statuses.' 
                                    . str_replace(' ', '_', $translation->status->status))) : '' !!}{!! $translation->status->id < 1000 ? ',' : '' !!}
                                @endif
                            @endforeach
                        
                        @else
                        <span>{{ trans('terms.index.notranslations') }}</span>
                        @endunless
                    </td>
                @else
                    <td></td>
                @endif
            </tr>
            @endforeach
            </tbody>
        </table>
        
        {{-- Generate pagination including filters --}}
        {!! $terms->appends($allFilters)->render() !!}

        {{-- Terms are empty --}}
        @else
            <div class="alert alert-warning" role="alert">
            {{-- Messages for the user if there are no terms to display --}}
            {!! isset($allFilters['menu_letter']) && isset($terms) && $terms->isEmpty() ? 
                trans('terms.index.otherletter') : '' !!}
            {!! isset($allFilters['search']) && isset($terms) && $terms->isEmpty() ? 
                trans('terms.index.somethingelse') : '' !!}

            {{-- If letter or search is not set, but we have menu letters displayed... --}}
            {!! !(isset($allFilters['menu_letter']))
                && !(isset($allFilters['search']))
                && isset($menuLetters) 
                && !($menuLetters->isEmpty()) ? 
                    trans('terms.index.letterorsearch') : '' !!}
            
            </div>
        @endif
    </div>
</div>
@endsection
