<div class="row">
    <div class="col-xs-12">
        @if(getenv('APP_ENV')=='production')
        @include('ads.adsense_right')
        @else
        <img src="http://lorempixel.com/300/300/technics/google-ad"
             class="img-responsive" 
             alt="Temp for ad" title="Temp for ad" />
        @endif
    </div>
    <div class="col-xs-12">
        @if(getenv('APP_ENV')=='production')
        @include('ads.contributor')
        @endif
    </div>
</div>