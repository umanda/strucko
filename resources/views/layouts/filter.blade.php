<div class="row">
    <div class="col-md-12">
        <form method="GET" action="/terms" class="form-inline">
            <div class="form-group">
                <label for="language_id">Language:</label>
                {!! Form::select('language_id', $languages->lists('ref_name', 'id'),
                isset($allFilters['language_id']) ? $allFilters['language_id'] : old('language_id'), 
                ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label for="scientific_field_id">Field:</label>
                {!! Form::select('scientific_field_id', $scientificFields,
                isset($allFilters['scientific_field_id']) ? $allFilters['scientific_field_id'] : old('scientific_field_id'), 
                ['id' => 'scientific_field_id', 'required' => 'required', 'class' => 'form-control']) !!}

            </div>
            <button type="submit" class="btn btn-default">Go</button>
        </form>
    </div>
</div>
