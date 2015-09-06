<div class="row margin-top-10">
    <div class="col-md-10">
        <form method="GET" action="/terms" class="form-inline">
            <div class="form-group">
                <input type="hidden" name="language_id" value="{{ $filters['language_id'] }}">
                <input type="hidden" name="scientific_field_id" value="{{ $filters['scientific_field_id'] }}">

                <input type="text" class="form-control" name="search" id="search" placeholder="Or search..."
                       value="{{ isset($filters['search']) ? $filters['search'] : old('search') }}">

                <button type="submit" class="btn btn-default">Search</button>
            </div>
        </form>
    </div>
</div>

