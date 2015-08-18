{!! csrf_field() !!}

<div class="form-group">
    <label for="term">Term:</label>
    <input type="text" id="term" name="term" maxlength="255" required="required"
           placeholder="Term" class="form-control"
           value="{{ isset($term) ? $term->term : old('term') }}">
</div>

<div class="form-group">
    <label for="language_id">Language:</label>

    {!! Form::select('language_id', $languages->lists('ref_name', 'id'),
            isset($term) ? $term->language_id : old('language_id'), 
            ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}

</div>

<div class="form-group">
    <label for="part_of_speech_id">Part of Speech:</label>
    <select id="part_of_speech_id" name="part_of_speech_id" required="required" class="form-control" >
        @if (isset($term))
        <option value="{{ $term->partOfSpeech->id }}" selected="selected">{{ $term->partOfSpeech->part_of_speech }}</option>
        @endif
        @foreach ($partOfSpeeches as $partOfSpeech)
        <option value="{{ $partOfSpeech->id }}">{{ $partOfSpeech->part_of_speech }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="scientific_field_id">Scientific field (category):</label>
    {!! Form::select('scientific_field_id', $scientificFields,
            isset($term) ? $term->scientific_field_id : old('scientific_field_id'), 
            ['id' => 'scientific_field_id', 'required' => 'required', 'class' => 'form-control']) !!}
    
</div>

<div class="form-group">
    <label for="abbreviation">Abbreviation (optional):</label>
    <input type="text" id="abbreviation" name="abbreviation" maxlength="255" 
           placeholder="Abbreviation" class="form-control"
           value="{{ isset($term) ? $term->abbreviation : old('abbreviation') }}">
</div>

<div class="form-group">
    <label for="definition">Definition (optional):</label>
    <textarea id="definition" name="definition" placeholder="Definition"
              rows="3" class="form-control">{{ old('definition') }}</textarea>

</div>

@if(isset($term))

<div class="form-group">
    <label for="existing_definitions">Existing definitions:</label>
    <ul id="existing_definitions" name="existing_definitions">
        @foreach ($term->synonym->definitions as $definition)
        <li><a href="#" class="btn btn-default">Edit</a> <a href="#" class="btn btn-danger">Delete</a>
            {{ $definition->definition }}</li>
        @endforeach
    </ul>
</div>



@endif