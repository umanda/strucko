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
                        @if ( ! $suggestedTerms->isEmpty())
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
                                
                                @foreach ($suggestedTerms as $suggestedTerm)
                                <tr>
                                    <td>
                                        {{ $suggestedTerm->term }} ({{ $suggestedTerm->language->ref_name }})
                                        <p>TODO other terms from this concept</p>
                                    </td>
                                    <td>
                                        @foreach ($suggestedTerm->mergeSuggestions as $mergeSuggestion)
                                            @foreach($mergeSuggestion->concept->terms as $conceptTerm)
                                            {{ $conceptTerm->term }} ({{ $conceptTerm->language->ref_name }})
                                            @endforeach
                                        @endforeach
                                    </td>
                                    <td> TODO Votes</td>
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
