
            <div class="form-group">
                <label for="language_id">Language:</label>
                {!! Form::select('language_id', $languages->lists('ref_name', 'id'),
                isset($termFilters['language_id']) ? $termFilters['language_id'] : old('language_id'), 
                ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label for="scientific_field_id">Field:</label>
                {!! Form::select('scientific_field_id', $scientificFields,
                isset($termFilters['scientific_field_id']) ? $termFilters['scientific_field_id'] : old('scientific_field_id'), 
                ['id' => 'scientific_field_id', 'required' => 'required', 'class' => 'form-control']) !!}

            </div>
            <button type="submit" class="btn btn-default">Go</button>
