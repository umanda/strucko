@extends('layouts.master')

@section('meta-description', 'Edit the term')

@section('title', 'Edit the term')

@section('content')
    <h2> Edit {!! $term->term !!}</h2>
    
    {!! Form::model($term, ['method' => 'PATCH', 
    'action' => ['TermsController@update', $term->slug_unique]]) !!}
    
    {!! Form::label('term', 'Term') !!}
    {!! Form::text('term', null, ['class'=>'form-control']) !!}
    
<!--    <form method="POST" action="{{ action('TermsController@update', ['slugUnique' => $term->slug_unique]) }}">
        <input type="hidden" name="_method" value="PATCH">
        
        {!! csrf_field() !!}
    -->
    <div class="form-group">
        <label for="term">Term:</label>
        <input type="text" id="term" name="term" maxlength="255" required="required"
               placeholder="Term" class="form-control"
               >
    </div>
    {!! Form::close() !!}
<!--    </form>-->
@endsection