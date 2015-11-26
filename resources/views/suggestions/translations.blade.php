@extends('layouts.master')

@section('meta-description', 'List and manage translation suggestions')

@section('title', 'Translatione suggestions')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('suggestions.menu')
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <form method="GET" action="/suggestions/translations" class="form-horizontal">
                            @include('suggestions.filter')
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        
                            <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th class="col-xs-3">Term</th>
                                    <th class="col-xs-4">Translation</th>
                                    <th class="col-xs-1 text-center">Votes</th>
                                    @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                        <th class="col-xs-1 text-center">Approve</th>
                                        <th class="col-xs-1 text-center">Reject</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($translations) && ! ($translations->isEmpty()))
                                    @foreach($translations as $translation)
                                        <tr>
                                            <td class="vertical-center-cell">
                                                <a class="btn-link btn-lg" href="{{ action('TermsController@show', ['slug' => $translation->term->slug]) }}">
                                                    {{ $translation->term->term }}</a>
                                                <br><small>suggested by <i>{{ $translation->user->name }}</i></small>
                                            </td>

                                            <td class="vertical-center-cell">
                                                <a href="{{ action('TermsController@show', $translation->translation->slug) }}">
                                                        {{ $translation->translation->term }}</a>
                                            </td>

                                            <td class="text-center vertical-center-cell">
                                                {{ $translation->votes_sum }}
                                            </td>

                                            @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                            <td class="text-center vertical-center-cell">
                                                todo
                                            </td>
                                            <td class="text-center vertical-center-cell">
                                                todo
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                {{--No merge suggestions --}}
                                    <td colspan="3"><span class="text-warning">No merge suggestions...</span></td>
                                @endif
                                
                            </tbody>
                        </table>
                        
                        @if(isset($mergeSuggestions) && ! ($mergeSuggestions->isEmpty()))
                            {!! $mergeSuggestions->appends($termFilters)->render() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
