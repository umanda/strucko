<form method="GET" action="{{ action('TermsController@index') }}">
    <input type="hidden" name="language_id"
           value="{{ isset($allFilters['language_id']) ? $allFilters['language_id'] : old('scientific_field_id') }}">
    <input type="hidden" name="scientific_field_id"
           value="{{ isset($allFilters['scientific_field_id']) ? $allFilters['scientific_field_id'] : old('scientific_field_id') }}">
    <input type="hidden" name="menu_letter"
           value="{{ isset($allFilters['menu_letter']) ? $allFilters['menu_letter'] : old('menu_letter') }}">
    <div class="form-group">
        <label for="translate_to_2">Translate to</label>
        {!! Form::select('translate_to',
        array_merge(['' => 'Choose language'], $languages->lists('ref_name', 'id')->forget($allFilters['language_id'])->toArray()),
        isset($allFilters['translate_to']) ? $allFilters['translate_to'] : old('translate_to'), 
        ['id' => 'translate_to_2', 'class' => 'form-control', 'onchange' => 'this.form.submit()']) !!}
    </div>
</form>