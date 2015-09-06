@extends('layouts.master')

@section('meta-description', 'All about ' . $term->term)

@section('title', $term->term . ' - definitions and translations')

@section('content')

<h3>{{ $term->term }}</h3>

<p> 
    {{ $term->synonym->language->ref_name }}, 
    {{ $term->synonym->scientificField->scientific_field }}, 
    {{ $term->synonym->partOfSpeech->part_of_speech }},
    {{ $term->status->status }}, 
    {{ $term->menu_letter }}
</p>
<h4>Abbreviation:</h4>
<p>{{ $term->abbreviation }}</p>
<h4>Synonyms (ID is {{ $term->synonym_id }}):</h4>
<ul>
@foreach ($term->synonym->terms as $synoynmTerm)
    <li>{{ $synoynmTerm->term }}</li>
@endforeach
</ul>
<h4>Sugessted by user:</h4>
<p>{{ $term->user->name }}</p>
<h4>Translations:</h4>
<ul>
@foreach ($term->synonym->translations as $translation)
        
    @foreach ($translation->terms as $translationTerm)
    <li><a href="/terms/{{ $translationTerm->slug_unique }}">
            {{ $translationTerm->term . ' synonym ' . $translationTerm->synonym_id .  ' status ' . $translation->pivot->status_id }}
        </a>
    </li>
    @endforeach

@endforeach
</ul>
<h4>Definitions:</h4>
<ul>
@foreach ($term->synonym->definitions as $definition)
    <li>{{ $definition->definition }}</li>
@endforeach
</ul>

@if (Auth::check() && ! (Auth::user()->role_id < 1000))

    <a class="btn btn-default" href="{{ action('TermsController@edit', ['slug_unique' =>
                $term->slug_unique]) }}">Edit</a>
    
    @include('terms.suggestions')
                
@elseif(Auth::check())

    @include('terms.suggestions')

@endif

@endsection