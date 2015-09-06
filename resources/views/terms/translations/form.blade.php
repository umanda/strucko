
{!! csrf_field() !!}

<input type="hidden" name="synonym_id" required="required" id="synonym_id" value="{{ $term->synonym_id }}">
<input type="hidden" name="scientific_field_id" required="required" id="synonym_id" value="{{ $term->synonym->scientific_field_id }}">
<input type="hidden" name="part_of_speech_id" required="required" id="part_of_speech_id" value="{{ $term->synonym->part_of_speech_id }}">

<div class="form-group">
    <label for="language_id">Language to translate to:</label>
    {!! Form::select('language_id', $languages->lists('ref_name', 'id'),
    Input::has('translation_id') ? Input::get('translation_id') : old('language_id'), 
    ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="term">Term</label>
    <input type="text" id="term" name="term" value="{{ old('term') }}" required="required" 
           maxlength="255" placeholder="Term" class="form-control">
</div>
<div class="form-group">
    <label for="abbreviation">Abbreviation (optional):</label>
    <input type="text" id="abbreviation" name="abbreviation" maxlength="255" 
           placeholder="Abbreviation" class="form-control"
           value="{{ old('abbreviation') }}">
</div>

