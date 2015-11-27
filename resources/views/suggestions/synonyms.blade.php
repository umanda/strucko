@extends('layouts.master')

@section('meta-description', 'List and manage synonym suggestions')

@section('title', 'Synonym suggestions')

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
                        <form method="GET" action="/suggestions/synonyms" class="form-horizontal">
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
                                    <th class="col-xs-4">Synonym</th>
                                    <th class="col-xs-1 text-center">Votes</th>
                                    @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                        <th class="col-xs-1 text-center">Approve</th>
                                        <th class="col-xs-1 text-center">Reject</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($synonyms) && ! ($synonyms->isEmpty()))
                                    @foreach($synonyms as $synonym)
                                        <tr>
                                            <td class="vertical-center-cell">
                                                <a class="btn-link btn-lg" href="{{ action('TermsController@show', ['slug' => $synonym->term->slug]) }}">
                                                    {{ $synonym->term->term }}</a>
                                                <br><small>suggested by <i>{{ $synonym->user->name }}</i></small>
                                            </td>

                                            <td class="vertical-center-cell">
                                                <a href="{{ action('TermsController@show', $synonym->synonym->slug) }}">
                                                        {{ $synonym->synonym->term }}</a>
                                            </td>

                                            <td class="text-center vertical-center-cell">
                                                {{ $synonym->votes_sum }}
                                            </td>

                                            @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                            <td class="text-center vertical-center-cell">
                                                <form method="POST" action="{{ action('ConceptsController@approveSynonym', [$synonym->id]) }}">
                                                    @include('suggestions.forms.approve')
                                                </form>
                                            </td>
                                            <td class="text-center vertical-center-cell">
                                                <form method="POST" action="{{ action('ConceptsController@rejectSynonym', [$synonym->id]) }}">
                                                    @include('suggestions.forms.reject')
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                {{--No synonyms --}}
                                    <td colspan="3"><span class="text-warning">No synonyms...</span></td>
                                    <td></td><td></td><td></td><td></td>
                                @endif
                                
                            </tbody>
                        </table>
                        
                        @if(isset($synonyms) && ! ($synonyms->isEmpty()))
                            {!! $synonyms->appends($allFilters)->render() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
