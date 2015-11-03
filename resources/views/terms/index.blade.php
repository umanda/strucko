@extends('layouts.master')

@section('meta-description', 'All terms in the Strucko Expert Dictionary.')

@section('title', 'All terms')

@section('content')

<div class="row">
    @include('layouts.filter')
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
<hr>
<div class="row">
    <div class="col-sm-12">
        <p class="btn-lg btn-info">Sorry, no terms in selected language and field</p>
    </div>
</div>
@else
<hr>
<div class="row">
    <div class="col-sm-12">
        <p class="btn-lg btn-info">Select language and field</p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        @if(isset($terms) && !($terms->isEmpty()))
        <h2 class="text-right">{{ $languages->lists('ref_name', 'id')->get($allFilters['language_id']) }}, 
            {{ collect(call_user_func_array('array_replace', $scientificFields))->get($allFilters['scientific_field_id']) }}
            {{ isset($allFilters['translate_to']) ? '- translated to ' . $languages->lists('ref_name', 'id')->get($allFilters['translate_to']) : '' }}
            {{ isset($allFilters['search']) ? '- results for ' . $allFilters['search']  : '' }}
            
        </h2>
        
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
                        <a class="btn-link btn-lg" href="{{ action('TermsController@show', ['slug' =>
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
                        @unless ($term->concept->terms->isEmpty())
                            @foreach ($term->concept->terms as $key => $translationTerm)
                                @if (is_last($term->concept->terms, $key))
                                    {{ $translationTerm->term }}
                                    {!! $translationTerm->status->id < 1000 ? status_warning($translationTerm->status->status) : '' !!}
                                @else
                                    {{ $translationTerm->term }}
                                    {!! $translationTerm->status->id < 1000 ? status_warning($translationTerm->status->status) : '' !!},
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
