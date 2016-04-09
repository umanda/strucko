
{!! csrf_field() !!}
{!! getLocaleInputField() !!}
<input type="hidden" name="scientific_field_id" value="{{ $term->scientific_field_id }}">
<input type="hidden" name="part_of_speech_id" value="{{ $term->part_of_speech_id }}">
<input type="hidden" name="language_id" value="{{ $term->language_id }}">

<div class="form-group">
    <label for="term_synonym">
        {{ trans('terms.suggestions.synonym') }}
        ({{ trans('languages.' . str_replace(' ', '_', $term->language->ref_name)) }})
    </label>
    <input type="text" id="term_synonym" name="term" value="{{ old('term') }}" required="required" 
           maxlength="255" 
           placeholder="{{ trans('terms.suggestions.synonym') }}" class="form-control" aria-describedby="synonymHelp">
    <span id="synonymHelp" class="help-block">
        {!! trans('terms.suggestions.synonymhelp') !!}
        
    </span>
</div>
<div class="checkbox">
    <label>
        {!! Form::checkbox('is_abbreviation', 1) !!}
        {{ trans('terms.suggestions.abbreviation') }}
    </label>
</div>