
<div class="col-md-6 hidden-xs">
    <div class="page-header text-center">
        <h1>{{ trans('header.logo') }} <small>{{ trans('header.tagline') }}</small></h1>
        <p>{{ trans('header.belowtagline') }}</p>
    </div>
</div>
<div class="col-md-6">
    @if(getenv('APP_ENV')=='production')
            @include('ads.adsense_header')
    @else
        <img src="http://lorempixel.com/450/177/technics/google-ad"
             class="img-responsive strucko-header"
             alt="Temp for ad" title="Temp for ad" />
    @endif
    
</div>