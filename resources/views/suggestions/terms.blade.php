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
                        <form method="GET" action="/suggestions/terms" class="form-inline">
                            @include('suggestions.filter')
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <ul>
                            @foreach ($terms as $term)
                            <li>{{ $term->term }} - {{ $term->votes()->sum('vote') }} 
                                @include('terms.votes.form_up') 
                                @include('terms.votes.form_down')
                            </li>
                            <form method="POST" action="{{ action('TermsController@approveTerm', [$term->slug_unique]) }}">
                                @include('suggestions.forms.approve')
                            </form>
                            <form method="POST" action="{{ action('TermsController@rejectTerm', [$term->slug_unique]) }}">
                                @include('suggestions.forms.reject')
                            </form>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection
