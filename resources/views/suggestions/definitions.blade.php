@extends('layouts.master')

@section('meta-description', 'List and vote for new definitions')

@section('title', 'New definition suggestions')

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
                        <form method="GET" action="/suggestions/definitions" class="form-inline">
                            @include('suggestions.filter')
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <i>TODO definition voting</i>
                        @if ( ! $concepts->isEmpty())
                        <ul>
                            @foreach ($concepts as $concept)
                            <li> <b>Terms:</b> 
                                @foreach ($concept->terms as $term)
                                    {{ $term->term }} ({{ $term->status->status }}) - {{ $term->language->ref_name }}
                                @endforeach
                                <br> <b>Definitions:</b>
                                @foreach ($concept->definitions as $definition)
                                    {{ $definition->definition }}, 
                                @endforeach
                                @include('votes.form_up') 
                                @include('votes.form_down')
                            </li>
                            <form method="POST" action="{{ action('TermsController@approveTerm', [$term->slug]) }}">
                                @include('suggestions.forms.approve')
                            </form>
                            <form method="POST" action="{{ action('TermsController@rejectTerm', [$term->slug]) }}">
                                @include('suggestions.forms.reject')
                            </form>
                            @endforeach
                        </ul>
                        @else
                        <p>No suggestions...</p>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
