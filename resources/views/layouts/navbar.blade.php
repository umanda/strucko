<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" 
                    data-toggle="collapse" data-target="#navbar" 
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">{{ trans('navbar.toggle') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ resolveUrlAsUrl('/') }}">
                {{ trans('navbar.logo') }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="{{ set_active('terms') }}">
                    @if(Session::has('allFilters'))
                    <a href="{{ resolveUrlAsAction('TermsController@index', 
                                Session::get('allFilters')) }}">
                        {{ trans('navbar.terms') }}</a>
                    @else
                    <a href="{{ resolveUrlAsAction('TermsController@index') }}">
                        {{ trans('navbar.terms') }}</a>
                    @endif
                </li>
                <li class="{{ set_active('terms/create') }}">
                    <a href="{{ resolveUrlAsUrl('/terms/create') }}">
                        {{ trans('navbar.newterm') }}</a>
                </li>
                <li class="{{ set_active('contact*') }}">
                    <a href="{{ resolveUrlAsUrl('/contact') }}">
                        {{ trans('navbar.contact') }}</a>
                </li>
                <li class="{{ set_active('about*') }}">
                    <a href="{{ resolveUrlAsUrl('/about') }}">
                        {{ trans('navbar.about') }}</a>
                </li>
                
                {{--Admin menu--}}
                @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                           role="button" aria-haspopup="true" aria-expanded="false">Admin menu <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ set_active('suggestions*') }}">
                                <a href="{{ resolveUrlAsAction('SuggestionsController@index') }}">
                                    Suggestions</a>
                            </li>
                            <li class="{{ set_active('scientific-areas*') }}">
                                <a href="/scientific-areas/">
                                    Areas and Fields</a>
                            </li>
                            <li class="{{ set_active('languages*') }}">
                                <a href="{{ resolveUrlAsUrl('/languages') }}">Languages</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                @endif
                
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ setLocaleInUrl() }}">{{ trans('navbar.english') }}</a></li>
                <li><a href="{{ setLocaleInUrl('hr') }}">{{ trans('navbar.croatian') }}</a></li>  
                @if (Auth::check())
                    <li><a href="{{ resolveUrlAsAction('UsersController@getStats') }}">{{ Auth::user()->name }}</a></li>
                    <li><a href="{{ resolveUrlAsUrl('/auth/logout') }}">{{ trans('navbar.logout') }}</a></li>
                @else
                    <li class="{{ set_active('auth/login') }}">
                        <a href="{{ resolveUrlAsUrl('/auth/login') }}">
                            {{ trans('navbar.login') }}</a>
                    </li>
                    <li class="{{ set_active('auth/register') }}">
                        <a href="{{ resolveUrlAsUrl('/auth/register') }}">
                            {{ trans('navbar.register') }}
                            <span class="sr-only">(current)</span></a>
                    </li>
                @endif
                
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>