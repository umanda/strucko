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
                <li><a href="{{ action('TermsController@index', Session::has('allFilters') ? Session::get('allFilters') : '') }}">Terms</a></li>
                <li><a href="/terms/create">Create</a></li>
                <li><a href="{{ action('TermsController@suggestions') }}">Suggestions</a></li>
                <li><a href="/scientific-areas/">Areas and Fields</a></li>
                <li><a href="/languages">Languages</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li><a href="#">{{ Auth::user()->name }}</a></li>
                    <li><a href="/auth/logout">Log out</a></li>
                @else
                    <li><a href="/auth/login">Log in</a></li>
                    <li class="active"><a href="/auth/register">Register <span class="sr-only">(current)</span></a></li>
                @endif
                
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>