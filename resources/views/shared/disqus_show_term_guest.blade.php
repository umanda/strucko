{{-- Disqus for guest users, with different page identifier --}}
@if(getenv('APP_ENV')=='production')
<hr>
<section>
    @include('shared.disqus', [
    'url' => action('TermsController@show', ['slug' => $term->slug]),
    'identifier' => $term->slug
    ])
</section>
@endif