@extends('layouts.master')

@section('meta-description', $indexMeta['description'])

@section('title', $indexMeta['title'])

@section('content')

<div class="row">
    <div class="col-xs-12">
        @if (isset($allFilters['language_id'])
            && isset($allFilters['scientific_field_id'])
            && isset($allFilters['translate_to']))
            <div class="row">
                {{-- Language Designations --}}
                <div class="col-xs-10 vertical-center">
                    <h2 class="text-left" style="padding: 0px 10px 15px 0px">
                        {{ trans('languages.' . str_replace(' ', '_', $language))  }}
                        {{ isset($allFilters['translate_to']) ? trans('terms.index.to') . ' '
                                    . trans('languages.' . $translateToLanguage) : '' }}
                    </h2>
                </div>
                {{-- Language Switcher --}}
                <div class="col-xs-2 text-center vertical-center">
                    <a href="{{ resolveUrlAsAction('TermsController@index', [
                        'language_id' => $allFilters['translate_to'],
                        'scientific_field_id' => $allFilters['scientific_field_id'],
                        'translate_to' => $allFilters['language_id']
                        ]) }}" class="btn btn-lg lang-switcher" title="{{ trans('terms.index.switchlanguage')  }}">
                        <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
            {{-- If we have menu letters, we have terms --}}
            @if(isset($menuLetters) && ! ($menuLetters->isEmpty()))
                <div class="row">
                    <div class="col-sm-7">
                        @include('layouts.menu')
                    </div>
                    <div class="col-sm-5">
                        @include('layouts.search')
                    </div>
                </div>
            @else
                {{-- No terms... --}}
                <div class="row">
                    <div class="col-xs-12 alert-warning text-center">
                        <p>{{ trans('terms.index.noterms') }}</p>
                    </div>
                </div>
                {{-- Choose available language to get terms --}}
                @include('terms.partials.select_language')
            @endif
            {{-- List resulting terms --}}
            @include('terms.partials.terms_results')
        @else
            {{-- Query params not set, choose Language --}}
            @include('terms.partials.select_language')
        @endif 
    </div>
</div>
@endsection
