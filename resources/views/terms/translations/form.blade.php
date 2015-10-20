
{!! csrf_field() !!}

<input type="hidden" name="concept_id" value="{{ $term->concept_id }}">
<input type="hidden" name="scientific_field_id" value="{{ $term->scientific_field_id }}">
<input type="hidden" name="part_of_speech_id" value="{{ $term->part_of_speech_id }}">

<div class="form-group">
    <label for="language_id">Language to translate to:</label>
    {!! Form::select('language_id', array_merge(['' => 'Choose language'], $languages->lists('ref_name', 'id')->toArray()),
    Input::has('translation_id') ? Input::get('translation_id') : old('language_id'), 
    ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="term_translation">Translation</label>
    <input type="text" id="term_translation" name="term" value="{{ old('term') }}" required="required" 
           maxlength="255" 
           placeholder="Translation {{ isset($term) ? 'for ' . $term->term : '' }}" class="form-control">
</div>
<div class="checkbox">
    <label>
        {!! Form::checkbox('is_abbreviation', 1) !!}
        This is abbreviation
    </label>
</div>

