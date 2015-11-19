@extends('layouts.master')

@section('meta-description', $indexMeta['description'])

@section('title', $indexMeta['title'])

@section('content')

<div class="row">
    @include('layouts.filter')
    @if (isset($allFilters['language_id']) && isset($allFilters['scientific_field_id']))
    <h2 class="text-right">
        {{ $language }}, 
        {{ $scientificField }}            
        {{ isset($allFilters['menu_letter']) ? '- ' . $menuLetter : '' }}
        {{ isset($allFilters['translate_to']) ? '- translated to ' . $translateToLanguage : '' }}
        {{ isset($allFilters['search']) ? '- results for ' . $search  : '' }}
    </h2>
    @endif
    <hr>
</div>
@if(isset($menuLetters) && ! ($menuLetters->isEmpty()))
<div class="row">
    <div class="col-sm-8">
        @include('layouts.menu')
    </div>
    <div class="col-sm-4">
        @include('layouts.search')
    </div>
</div>
@elseif (isset($allFilters['language_id']) && isset($allFilters['scientific_field_id']))
<div class="row">
    <div class="col-sm-12">
        <p class="btn-lg btn-info">Sorry, no terms in selected language and field</p>
        <p class="text-center">
            <a class="btn btn-success" href="/terms/create">Suggest a new term</a>
        </p>
    </div>
</div>
@else
<div class="row">
    <div class="col-sm-12">
        <p class="btn-lg btn-info">Select language and field</p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        
        @if(isset($terms) && !($terms->isEmpty()))
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th class="col-xs-5 vertical-center-cell">Terms</th>
                    <th class="col-xs-7">
                        @include('terms.translate_to_2')
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($terms as $term)
            <tr>
                <td class="vertical-center-cell">
                    @if (isset($allFilters['translate_to']))
                        <small>{{ $term->partOfSpeech->part_of_speech }}</small><br>
                        <a class="btn-link btn-lg" lang="{{ $term->language->part1 }}"
                           href="{{ action('TermsController@show', ['slug' =>
                            $term->slug, 'translate_to' => $allFilters['translate_to'] ]) }}">
                        {{ $term->term }}</a>
                        {!! $term->status->id < 1000 ? status_warning($term->status->status) : '' !!}
                    @else
                        <small>{{ $term->partOfSpeech->part_of_speech }}</small><br>
                        <a class="btn-link btn-lg" href="{{ action('TermsController@show', ['slug' => $term->slug]) }}">{{ $term->term }}</a>
                        {!! $term->status->id < 1000 ? status_warning($term->status->status) : '' !!}
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
                                    {!! $translation->status->id < 1000 ? status_warning($translation->status->status) : '' !!}
                                @else
                                <span lang="{{ $translation->translation->language->part1 }}">
                                    {{ $translation->translation->term }}{!! $translation->status->id < 1000 ? '' : ',' !!}
                                </span>
                                {!! $translation->status->id < 1000 ? status_warning($translation->status->status) . ',' : '' !!}
                                @endif
                            @endforeach
                        
                        @else
                        <span>...no translation</span>
                        @endunless
                    </td>
                @else
                    <td></td>
                @endif
            </tr>
            @endforeach
            </tbody>
        </table>
        
        {!! $terms->appends($allFilters)->render() !!}

        {{-- Terms are empty --}}
        @else
            {{-- Messages for the user if there are no terms to display --}}
            {!! isset($allFilters['menu_letter']) && $terms->isEmpty() ? '<p class="btn-lg btn-info">No results, try some other letter</p>' : '' !!}
            {!! isset($allFilters['search']) && $terms->isEmpty() ? '<p class="btn-lg btn-info">No results, try something else</p>' : '' !!}

            {{-- If letter or search is not set, but we have menu letters displayed... --}}
            {!! !(isset($allFilters['menu_letter']))
            && !(isset($allFilters['search']))
            && isset($menuLetters) 
            && !($menuLetters->isEmpty()) ? '<p class="btn-lg btn-info">Select a letter or search for specific term</p>' : '' !!}

        @endif
    </div>
</div>
@endsection
