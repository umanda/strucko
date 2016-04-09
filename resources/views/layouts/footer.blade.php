<footer class="footer">
    <div class="well">
        <a href="#" class="pull-right">{{trans('home.footer.backtotop')}}</a>
        
        <ul class="nav nav-pills">
            <li role="presentation"><a href="{{ resolveUrlAsUrl('/terms-of-use') }}" 
                                       class="text-muted">{{trans('home.footer.tou')}}</a></li>
            <li role="presentation"><a href="{{ resolveUrlAsUrl('/privacy-policy') }}"
                                       class="text-muted">{{trans('home.footer.pp')}}</a></li>
            <li role="presentation"><a href="{{ resolveUrlAsUrl('/cookie-policy') }}"
                                       class="text-muted">{{trans('home.footer.cp')}}</a></li>
            <li role="presentation"><a href="{{ resolveUrlAsUrl('/disclaimer') }}"
                                       class="text-muted">{{trans('home.footer.disclaimer')}}</a></li>
        </ul>
        
        {!! trans('home.footer.licence') !!}
        
        
    </div>
    
</footer>