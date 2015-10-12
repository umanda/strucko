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
        <br>
        <p>Sorry, no terms in selected language and field...</p>
    </div>
</div>
@else
<hr>
<div class="row">
    <div class="col-sm-12">
        <br>
        <p>Please select language and field...</p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        @if(isset($terms) && !($terms->isEmpty()))
        <h2>Terms in {{ $languages->lists('ref_name', 'id')->get($allFilters['language_id']) }}, 
            {{ collect(call_user_func_array('array_replace', $scientificFields))->get($allFilters['scientific_field_id']) }}
            {{ isset($allFilters['translate_to']) ? '- translated to ' . $languages->lists('ref_name', 'id')->get($allFilters['translate_to']) : '' }}
            {{ isset($allFilters['search']) ? '- results for ' . $allFilters['search']  : '' }}
            
        </h2>
            
        <ul class="list-unstyled">
            @foreach($terms as $term)
            <li>
                    @if (isset($allFilters['translate_to']))
                    <a class="btn" href="{{ action('TermsController@show', ['slug' =>
                            $term->slug, 'translate_to' => $allFilters['translate_to'] ]) }}">
                        {{ $term->term }}
                    
                    </a>
                        @unless ($term->concept->terms->isEmpty())
                            {{ $allFilters['translate_to'] }}.
                            @foreach ($term->concept->terms as $key => $translationTerm)
                                @if (is_last($term->concept->terms, $key))
                                    {{ $translationTerm->term }}
                                @else
                                    {{ $translationTerm->term }},
                                @endif
                            @endforeach
                        @else
                        <span>...no translation</span>
                        @endunless
                    @else
                    <a class="btn" href="{{ action('TermsController@show', ['slug' => $term->slug]) }}">{{ $term->term }}</a>
                    @endif

                </li>
            @endforeach
        </ul>
            {!! $terms->appends($allFilters)->render() !!}
        
            {{-- Terms are empty --}}
            @else
                {{-- Messages for the user if there are no terms to display --}}
                {!! isset($allFilters['menu_letter']) && $terms->isEmpty() ? '<p>No results, try some other letter...</p>' : '' !!}
                {!! isset($allFilters['search']) && $terms->isEmpty() ? '<p>No results, try something else...</p>' : '' !!}
                
                {{-- If letter or search is not set, but we have menu letters displayed... --}}
                {!! !(isset($allFilters['menu_letter']))
                && !(isset($allFilters['search']))
                && isset($menuLetters) 
                && !($menuLetters->isEmpty()) ? '<p>Please select some letter or search for specific term</p>' : '' !!}
        
            @endif
    </div>
</div>
@endsection
