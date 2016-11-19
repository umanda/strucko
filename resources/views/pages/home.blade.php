@extends('layouts.master')

@section('meta-description', trans('home.description'))
@section('meta-keywords', trans('home.keywords'))
@section('title', trans('home.title'))

@section('content')
<div class="row">
    <div class="col-xs-12">
        <article>
            <h2 id="home_header_1">{{trans('home.header1')}}, searching for: @{{ home_header_1 }}</h2>
            <div class="row">
                @include('layouts.filter_home')
            </div>
        </article>
        <article>
            <h2>{{ trans('home.header2') }}</h2>
                <ul>

                    @foreach($latestTerms as $latestTerm)
                        @if( null != Session::get('allFilters.translate_to') && $latestTerm->language_id != Session::get('allFilters.translate_to'))
                        <li><a href="{{ resolveUrlAsAction('TermsController@show', [
                            'slug' => $latestTerm->slug,
                            'translate_to' => Session::get('allFilters.translate_to')]
                        ) }}"
                            lang="{{ $latestTerm->language->part1 }}">
                                {{ $latestTerm->term }}</a> 
                            ({{ trans('languages.' . str_replace(' ', '_', $latestTerm->language->ref_name)) }})
                        </li>
                        @else
                        <li><a href="{{ resolveUrlAsAction('TermsController@show', [
                            'slug' => $latestTerm->slug])}}"
                            lang="{{ $latestTerm->language->part1 }}">
                                {{ $latestTerm->term }}</a> 
                            ({{ trans('languages.' . str_replace(' ', '_', $latestTerm->language->ref_name)) }})
                        </li>
                        @endif
                    @endforeach
                </ul>
        </article>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <article>
            <h2>{{trans('home.header3')}}</h2>
            {!!  trans('home.introduction') !!}
        </article>
        @if(getenv('APP_ENV')=='production')
            <hr>
            <section>
                @include('shared.disqus', [
                    'url' => action('PagesController@getHome'),
                    'identifier' => 'home'
                ])
            </section>
        @endif
    </div>
</div>
@endsection
