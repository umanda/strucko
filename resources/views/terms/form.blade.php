{!! csrf_field() !!}

{!! getLocaleInputField() !!}

<div class="form-group">
    <label for="term">
        {{ trans('terms.create.term') }}:
    </label>
    <input type="text" id="term" name="term" maxlength="255" required="required"
           placeholder="{{ trans('terms.create.term') }}" class="form-control" autocomplete="off"
           value="{{ isset($term) ? $term->term : old('term') }}">
</div>
<div class="checkbox">
    <label>
        {!! Form::checkbox('is_abbreviation', 1, isset($term) ? $term->is_abbreviation : old('is_abbreviation')) !!}
        {{ trans('terms.create.abbreviation') }}
    </label>
</div>
<div class="form-group">
    <label for="language_id">{{ trans('terms.create.language') }}:</label>
    {!! Form::select('language_id', ['' => trans('terms.create.chooselanguage'),
                        'eng' => trans('languages.English'),
                        'hrv' => trans('languages.Croatian')],
                isset($term) ? $term->language_id : old('language_id'), 
                ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="part_of_speech_id">{{ trans('terms.create.partofspeech') }}:</label>
    <select id="part_of_speech_id" name="part_of_speech_id" required="required" class="form-control" >
        <option value="">{{ trans('terms.create.choosepartofspeech') }}</option>
        @if (isset($term))
        <option value="{{ $term->part_of_speech_id }}" selected="selected">
            {{ trans('partofspeeches.' . str_replace(' ', '_', $term->partOfSpeech->part_of_speech)) }}
        </option>
        @endif
        @foreach ($partOfSpeeches as $partOfSpeech)
        <option value="{{ $partOfSpeech->id }}">
            {{ trans('partofspeeches.' . str_replace(' ', '_', $partOfSpeech->part_of_speech)) }}
        </option>
        @endforeach
    </select>
</div>

<input type="hidden" name="scientific_field_id" value="19">

@if(isset($term))

<div class="form-group">
    <label for="existing_definitions">{{ trans('terms.create.existingdefinitions') }}:</label>
    <ul id="existing_definitions" name="existing_definitions">
        @foreach ($term->concept->definitions as $definition)
        <li><a href="#" class="btn btn-default">
                {{ trans('terms.create.edit') }}</a>
            <a href="#" class="btn btn-danger">
                {{ trans('terms.create.delete') }}</a>
            
            {{ $definition->definition }}
        </li>
        @endforeach
    </ul>
</div>

@endif