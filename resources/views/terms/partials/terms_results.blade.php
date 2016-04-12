{{-- Show the results if available --}}
<div class="row">
    <div class="col-sm-12">
        @if(isset($terms) && !($terms->isEmpty()))
            <h3>
                {{ trans('terms.index.results') }}
                <i>
                    {{ isset($allFilters['menu_letter']) ? $menuLetter : '' }}
                    {{ isset($allFilters['search']) ? $search : '' }}
                </i>
            </h3>
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-6 vertical-center-cell">
                            {{ trans('languages.' . str_replace(' ', '_', $language)) }}
                        </th>
                        <th class="col-xs-6">
                            {{ trans('languages.' . str_replace(' ', '_', $translateToLanguage)) }}
                            {{-- Not used with only two languages: --}}
                            {{-- @include('terms.translate_to_2') --}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($terms as $term)
                <tr>
                    <td class="vertical-center-cell">
                        @if (isset($allFilters['translate_to']))
                            <small>
                                {{ trans('partofspeeches.' 
                                . str_replace(' ', '_', $term->partOfSpeech->part_of_speech)) }}
                            </small>
                            <br>
                            <a class="btn-link btn-lg" lang="{{ $term->language->part1 }}"
                               href="{{ resolveUrlAsAction('TermsController@show', ['slug' =>
                                $term->slug, 'translate_to' => $allFilters['translate_to'] ]) }}">
                            {{ $term->term }}</a>
                            {!! $term->status->id < 1000 ? 
                            status_warning(trans('statuses.' . str_replace(' ', '_', $term->status->status))) : '' !!}
                        @else
                            <small>{{ trans('partofspeeches.' 
                                . str_replace(' ', '_', $term->partOfSpeech->part_of_speech)) }}</small><br>
                            <a class="btn-link btn-lg" 
                               href="{{ resolveUrlAsAction('TermsController@show', 
                                        ['slug' => $term->slug]) }}">{{ $term->term }}</a>
                            {!! $term->status->id < 1000 ? 
                            status_warning(trans('statuses.' . str_replace(' ', '_', $term->status->status))) : '' !!}
                        @endif
                    </td>
                    @if (isset($allFilters['translate_to']))
                        <td class="vertical-center-cell">
                            @unless ($term->translations->isEmpty())

                                @foreach ($term->translations as $key => $translation)
                                    @if (is_last($term->translations, $key))
                                    <a lang="{{ $translation->translation->language->part1 }}"
                                       href="{{ resolveUrlAsUrl('terms/' . $translation->translation->slug, [
                                        'translate_to' => $term->language_id
                                       ]) }}">
                                        {{ $translation->translation->term }}</a>
                                    {!! $translation->status->id < 1000 ? 
                                        status_warning(trans('statuses.' . str_replace(' ', '_', $translation->status->status))) : '' !!}
                                    @else
                                    <a lang="{{ $translation->translation->language->part1 }}"
                                       href="{{ resolveUrlAsUrl('terms/' . $translation->translation->slug, [
                                        'translate_to' => $term->language_id
                                       ]) }}">
                                        {{ $translation->translation->term }}</a>{!! $translation->status->id < 1000 ? '' : ',' !!}
                                    {!! $translation->status->id < 1000 ? 
                                        status_warning(trans('statuses.' 
                                        . str_replace(' ', '_', $translation->status->status))) : '' !!}{!! $translation->status->id < 1000 ? ',' : '' !!}
                                    @endif
                                @endforeach

                            @else
                            <span>{{ trans('terms.index.notranslations') }}</span>
                            @endunless
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>

            {{-- Generate pagination including filters --}}
            {{-- Do str_replace because of the 301 redirection on production server --}}
            {!! str_replace('/?', '?', $terms->appends($allFilters)->render()) !!}
            
        @else
            {{-- Terms are empty --}}
            <div class="alert alert-warning" role="alert">
            {{-- Messages for the user if there are no terms to display --}}
            {!! isset($allFilters['menu_letter']) && isset($terms) && $terms->isEmpty() ? 
                trans('terms.index.otherletter') : '' !!}
            {!! isset($allFilters['search']) && isset($terms) && $terms->isEmpty() ? 
                trans('terms.index.somethingelse') : '' !!}

            {{-- If letter or search is not set, but we have menu letters displayed... --}}
            {!! !(isset($allFilters['menu_letter']))
                && !(isset($allFilters['search']))
                && isset($menuLetters) 
                && !($menuLetters->isEmpty()) ? 
                    trans('terms.index.letterorsearch') : '' !!}
            
            </div>
        @endif
    </div>
</div>