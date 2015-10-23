@extends('layouts.master')

@section('meta-description', 'List and vote for new terms')

@section('title', 'New term suggestions')

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
                        <form method="GET" action="/suggestions/terms" class="form-horizontal">
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
                                    <th class="col-xs-9">Terms</th>
                                    <th class="col-xs-1 text-center">Votes</th>
                                    
                                    @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                        <th class="col-xs-1 text-center">Approve</th>
                                        <th class="col-xs-1 text-center">Reject</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($terms as $term)
                                <tr>
                                    <td class="vertical-center-cell">
                                        <small>{{ $term->partOfSpeech->part_of_speech }}</small><br>
                                        <a class="btn-link btn-lg" href="{{ action('TermsController@show', ['slug' => $term->slug]) }}">{{ $term->term }}</a>
                                        <br><small>by <i>{{ $term->user->name }}</i></small>
                                    </td>
                                    
                                    <td class="text-center vertical-center-cell">
                                        {{ $term->votes_sum }}
                                    </td>
                                    
                                    @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                                    <td class="text-center vertical-center-cell">
                                        <form method="POST" action="{{ action('TermsController@approveTerm', [$term->slug]) }}">
                                            @include('suggestions.forms.approve')
                                        </form>
                                    </td>
                                    <td class="text-center vertical-center-cell">
                                        <form method="POST" action="{{ action('TermsController@rejectTerm', [$term->slug]) }}">
                                            @include('suggestions.forms.reject')
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $terms->appends($termFilters)->render() !!}
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
