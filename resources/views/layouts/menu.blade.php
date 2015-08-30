<ul class="nav nav-pills">
    @foreach ($menuLetters as $menu_letter)
        <li role="presentation" 
            class="@if (isset($filters['menu_letter']))
                    @if ($menu_letter == $filters['menu_letter'])
                        {{ 'active' }}
                    @endif
                @endif">
            <a href="{{ action('TermsController@index',[
                    'language_id' => $language_id, 
                    'scientific_field_id' => $scientific_field_id, 
                    'menu_letter' => $menu_letter
                    ]) 
                    }}">
                {{ $menu_letter }}
            </a>
        </li>
    @endforeach
  
</ul>