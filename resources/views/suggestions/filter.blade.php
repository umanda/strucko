
        <div class="form-group">
            <label for="language_id" class="col-sm-2 control-label">Language:</label>
            <div class="col-sm-10">
                {!! Form::select('language_id', array_merge(['' => 'Choose language'], $languages->lists('ref_name', 'id')->toArray()),
                isset($termFilters['language_id']) ? $termFilters['language_id'] : old('language_id'), 
                ['id' => 'language_id', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="scientific_field_id" class="col-sm-2 control-label">Field:</label>
            <div class="col-sm-10">
                {!! Form::select('scientific_field_id', array_merge(['' => 'Choose field'], $scientificFields),
                isset($termFilters['scientific_field_id']) ? $termFilters['scientific_field_id'] : old('scientific_field_id'), 
                ['id' => 'scientific_field_id', 'class' => 'form-control']) !!}
            </div>
        </div>
       

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Set filters</button>
            </div>
        </div>
