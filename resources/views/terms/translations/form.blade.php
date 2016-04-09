
{!! csrf_field() !!}
{!! getLocaleInputField() !!}
<input type="hidden" name="concept_id" value="{{ $term->concept_id }}">
<input type="hidden" name="scientific_field_id" value="{{ $term->scientific_field_id }}">
<input type="hidden" name="part_of_speech_id" value="{{ $term->part_of_speech_id }}">
<input type="hidden" name="language_id" value="{{ $translateToLanguage['id'] }}">


<div class="form-group">
    <label for="term_translation">
        {{ trans('terms.suggestions.translation') }}
        ({{ trans('languages.' . str_replace(' ', '_', $translateToLanguage['ref_name'])) }})
    </label>
    <input type="text" id="term_translation" name="term" value="{{ old('term') }}" required="required" 
           maxlength="255" class="form-control"
           placeholder="{{ trans('terms.suggestions.translation') }}">
</div>
<div class="checkbox">
    <label>
        {!! Form::checkbox('is_abbreviation', 1) !!}
        {{ trans('terms.suggestions.abbreviation') }}
    </label>
</div>

