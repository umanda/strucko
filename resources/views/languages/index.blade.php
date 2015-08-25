@extends('layouts.master')

@section('meta-description', 'Languages used in the Strucko Dictionary.')

@section('title', 'Languages')

@section('content')
    <h2>List of languages included in Strucko</h2>
    <p>Source: <a href="http://www-01.sil.org/iso639-3/" target="_blank">http://www-01.sil.org/iso639-3/</a> 
        (only individual and living languages from ISO 639-3 are included)</p>
    @if ( Auth::check() && ! (Auth::user()->role_id < 1000) )
    <a class="btn btn-default" href="{{ action('LanguagesController@create') }}">Create new language</a>
    @endif
    
    @foreach($languages as $language)
    <h3><a href="{{ route('languages.show', $language->id) }}">{{ $language->ref_name }}</a>
        <span class="label label-warning">{{ $language->active ? '' : 'Inactive' }}</span>
    </h3>
    @endforeach
    
    {!! $languages->render() !!}
    
@endsection