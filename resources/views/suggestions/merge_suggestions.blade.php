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
                        <form method="GET" action="/suggestions/merge-suggestions" class="form-inline">
                            @include('suggestions.filter')
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table-borderless table-responsive">
                            <thead>
                                <tr>
                                    <th>Original term(s)</th>
                                    <th>Suggested merges</th>
                                    <th>Votes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($synonyms as $synonym)
                            <tr>
                                <td>
                                    @foreach ($synonym->terms as $term)
                                        {{ $term->term }},
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($synonym->mergeSuggestions as $mergeSuggestion)
                                        @foreach ($mergeSuggestion->mergedSynonym->terms as $mergedTerm)
                                          {{ $mergedTerm->term }},
                                        @endforeach
                                    @endforeach
                                </td>
                                <td> TODO Votes</td>
                                <td>TODO Actions</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
