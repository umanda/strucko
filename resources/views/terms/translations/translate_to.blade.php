<form method="GET" action="{{ action('TermsController@show', [$term->slug]) }}">
    <div class="form-group">
        <label class="sr-only" for="translate_to">Translations</label>
        {!! Form::select('translate_to', array_merge(['' => 'Choose language'], $languages->lists('ref_name', 'id')->toArray()),
        Input::has('translate_to') ? Input::get('translate_to') : old('translate_to'), 
        ['id' => 'translate_to', 'required' => 'required', 'class' => 'form-control', 'onchange' => 'this.form.submit()']) !!}
    </div>
</form>