
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
            <label for="status_id" class="col-sm-2 control-label">Status:</label>
            <div class="col-sm-10">
                {!! Form::select('status_id', [ '' => 'Choose status'] + $statuses,
                isset($termFilters['status_id']) ? $termFilters['status_id'] : old('status_id'), 
                ['id' => 'status_id', 'class' => 'form-control']) !!}
            </div>
        </div>
        @if(Request::is('suggestions/translations'))
            <div class="form-group">
                <label for="translate_to" class="col-sm-2 control-label">Translate to:</label>
                <div class="col-sm-10">
                    {!! Form::select('translate_to', [ '' => 'Choose language'] + array_merge(['' => 'Choose language'], $languages->lists('ref_name', 'id')->toArray()),
                    isset($termFilters['translate_to']) ? $termFilters['translate_to'] : old('translate_to'), 
                    ['id' => 'translate_to', 'class' => 'form-control']) !!}
                </div>
            </div>
        @endif

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Set filters</button>
            </div>
        </div>
