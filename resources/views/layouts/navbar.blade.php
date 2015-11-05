<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Strucko</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="{{ set_active('terms') }}">
                    @if(Session::has('allFilters'))
                    <a href="{{ action('TermsController@index', Session::get('allFilters') ) }}">Terms</a>
                    @else
                    <a href="{{ action('TermsController@index') }}">Terms</a>
                    @endif
                </li>
                <li class="{{ set_active('terms/create') }}"><a href="/terms/create">New Term</a></li>
                <li><a href="/contact">Contact</a></li>
                
                {{--Admin menu--}}
                @if (Auth::check() && ! (Auth::user()->role_id < 1000))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" 
                           role="button" aria-haspopup="true" aria-expanded="false">Admin menu <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ set_active('suggestions*') }}"><a href="{{ action('SuggestionsController@index') }}">Suggestions</a></li>
                            <li class="{{ set_active('scientific-areas*') }}"><a href="/scientific-areas/">Areas and Fields</a></li>
                            <li class="{{ set_active('languages*') }}"><a href="/languages">Languages</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                @endif
                
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li><a href="/home">{{ Auth::user()->name }}</a></li>
                    <li><a href="/auth/logout">Log out</a></li>
                @else
                    <li class="{{ set_active('auth/login') }}"><a href="/auth/login">Log in</a></li>
                    <li class="{{ set_active('auth/register') }}"><a href="/auth/register">Register <span class="sr-only">(current)</span></a></li>
                @endif
                
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>