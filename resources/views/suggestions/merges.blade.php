@extends('layouts.master')

@section('meta-description', 'List and manage all synonym merge suggestions')

@section('title', 'Synonym merge suggestions')

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
                        <form method="GET" action="/suggestions/merges" class="form-inline">
                            @include('suggestions.filter')
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @if ( ! $suggestedTerms->isEmpty())
                        <table class="table-borderless table-responsive">
                            <thead>
                                <tr>
                                    <th>Original term(s)</th>
                                    <th>Suggested merges</th>
                                    <th>More info</th>
                                    <th>Votes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($suggestedTerms as $suggestedTerm)
                                <tr>
                                    <td>
                                        <a class="btn btn-link" href="{{ action('TermsController@show', $suggestedTerm->slug) }}">
                                        {{ $suggestedTerm->term }} ({{ $suggestedTerm->language->ref_name }})
                                        </a>
                                    </td>
                                    <td>
                                        @foreach ($suggestedTerm->mergeSuggestions as $mergeSuggestion)
                                            @foreach($mergeSuggestion->concept->terms as $conceptTerm)
                                            {{ $conceptTerm->term }} ({{ $conceptTerm->language->ref_name }})
                                            @endforeach
                                            <form method="POST" action="{{ action('MergeSuggestionsController@voteUp', [$mergeSuggestion->id]) }}">
                                            @include('votes.form_up_naked')
                                            </form>
                                            <form method="POST" action="{{ action('MergeSuggestionsController@voteDown', [$mergeSuggestion->id]) }}">
                                            @include('votes.form_down_naked')
                                            </form>
                                        @endforeach
                                    </td>
                                    <td><a class="btn" href="{{ action('MergeSuggestionsController@show', $mergeSuggestion->id) }}" >Details</a></td>
                                    <td>
                                        ...
                                    </td>
                                    <td>TODO Actions</td>
                                </tr>
                                @endforeach
                                @else
                                    <p>No suggestions...</p>
                            </tbody>
                        </table>
                        
                        @endif
                            
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
