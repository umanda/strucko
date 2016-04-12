<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h2>{{ trans('terms.index.selectlanguage') }}</h2>
            </div>
        </div>
        <div class="row text-center btn-group-lg">
            <div class="col-xs-6">
                <h3>
                    <a class="thumbnail" role="button"
                       href="{{ resolveUrlAsUrl('/terms', [
                                'language_id' => 'eng',
                                'scientific_field_id' => '19',
                                'translate_to' => 'hrv'
                            ]) }}">{{ trans('languages.English') }}</a>
                </h3>
            </div>
            <div class="col-xs-6">
                <h3>
                <a class="thumbnail" role="button"
                   href="{{ resolveUrlAsUrl('/terms', [
                            'language_id' => 'hrv',
                            'scientific_field_id' => '19',
                            'translate_to' => 'eng'
                        ]) }}">{{ trans('languages.Croatian') }}</a>
                </h3>
            </div>
        </div>
    </div>
</div>