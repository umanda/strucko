
{!! csrf_field() !!}

<input type="hidden" name="scientific_field_id" value="{{ $term->scientific_field_id }}">
<input type="hidden" name="part_of_speech_id" value="{{ $term->part_of_speech_id }}">
<input type="hidden" name="language_id" value="{{ $term->language_id }}">

<div class="form-group">
    <label for="term_synonym">Synonym</label>
    <input type="text" id="term_synonym" name="term" value="{{ old('term') }}" required="required" 
           maxlength="255" 
           placeholder="Synonym {{ isset($term) ? 'for ' . $term->term : '' }}" class="form-control" aria-describedby="synonymHelp">
    <span id="synonymHelp" class="help-block">
        Synonym is a word or phrase that means exactly or nearly the same as 
        another word or phrase in the same language. 
        For example <i>shut</i> is a synonym of <i>close</i>.
    </span>
</div>
<div class="checkbox">
    <label>
        {!! Form::checkbox('is_abbreviation', 1) !!}
        This is abbreviation
    </label>
</div>