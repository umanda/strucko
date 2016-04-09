<div class="row margin-top-10 ">
    <div class="col-md-12">
        <nav>
            <ul class="nav nav-pills">
                @foreach ($menuLetters as $menuLetter)
                <li role="presentation" 
                    class="
                    @if (isset($allFilters['menu_letter']))
                        @if ($menuLetter == $allFilters['menu_letter'])
                            {{ 'active' }}
                        @endif
                    @endif
                    ">
                    <a href="{{ resolveUrlAsAction('TermsController@index', 
                                array_merge($menuLetterFilters, ['menu_letter' => $menuLetter])) 
                       }}">
                        {{ urldecode($menuLetter) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>