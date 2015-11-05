@extends('layouts.master')

@section('meta-description', 'Welcome to the Strucko - the Expert Dictionary')

@section('title', 'Strucko - the Expert Dictionary')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <article>
            <h2>Popular languages and scientific fields</h2>
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-xs-6 col-md-3 text-center">
                        <h3>
                            <a href="{{action('TermsController@index', ['language_id' => $category->language_id, 'scientific_field_id' => $category->scientific_field_id])}}" 
                               class="thumbnail">
                                {{ $category->language->ref_name }},
                                <br> {{ $category->scientificField->scientific_field }}
                                <br> <small>{{ $category->count }} term(s)</small>
                            </a>
                        </h3>
                  </div>
                @endforeach
            </div>
        </article>
        
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <article>
            <h2>Introducing Strucko - The Expert Dictionary </h2>
            <p>
                Although there are several great (and free) translation services, and 
                thousands of online dictionaries available to us, there is still a 
                huge gap in good online translations for technical or very specific 
                terms related to some area, field, craft, occupation, etc.
            </p>
            <p>
                Since the technology is improving on a day to day basis, and every day
                we have new discoveries which are named in one language, there is a 
                delay in <i>official</i> translations to other languages. Those
                official translations can sometimes be cumbersome, clumsy or awkward.
                Because of all that, translators still find it difficult to find 
                appropriate translations in cases when they need to translate professional text. 
            </p>
            <p>
                This is where Strucko comes into play. Strucko is open, free and 
                community driven expert dictionary. As a guest, you are free to see all approved
                terms, translations and definitions. If you want to contribute to the dictionary, 
                you can create an account which will give you permissions to vote on all terms and their
                translations, and also to suggest new terms, translations and definitions.
                By voting, you participate in the process of defining the best term and
                translation for particular concept. And that's why Strucko is great -
                it shows us the opinion of the community!
            </p>
            <p>
                Feel free to contact us if you have any suggestion.
                <br>
                Marko I.
            </p>
        </article>
        @if(getenv('APP_ENV')=='production')
            <hr>
            <section>
                @include('layouts.disqus', [
                    'url' => action('PagesController@getHome'),
                    'identifier' => ''
                ])
            </section>
        @endif
    </div>
</div>
@endsection


