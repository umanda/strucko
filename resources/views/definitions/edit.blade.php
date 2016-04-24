@extends('layouts.master')

@section('meta-description', trans('definitions.edit.description'))

@section('title', trans('definitions.edit.title'))

@section('content')
<h2>{{ trans('definitions.edit.header') }} <em>{{ $term->term }}</em></h2>

<form method="POST" action="{{ action('DefinitionsController@update', ['id' => $definition->id]) }}">

    <input type="hidden" name="_method" value="PATCH">

    @include('definitions.form')

    <div class="form-group">
        <input type="submit" id="submit" name="submit" 
               value="{{ trans('definitions.edit.savedefinition') }}"
               class="btn btn-primary">
        <a class="btn btn-default" 
           href="{{ resolveUrlAsAction('TermsController@show', ['slug' => $term->slug]) }}">
            {{ trans('definitions.edit.cancel') }}</a>
    </div>

</form>
<hr>

@endsection