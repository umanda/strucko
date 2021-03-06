<div class="row margin-top-10">
    <div class="col-sm-12">
        <form method="GET" action="/terms" >
            <input type="hidden" name="language_id" value="{{ $allFilters['language_id'] }}">
            
            <input type="hidden" name="scientific_field_id" value="{{ $allFilters['scientific_field_id'] }}">
            
            @if (isset($allFilters['translate_to']) && ! (empty($allFilters['translate_to'])))
            <input type="hidden" name="translate_to" value="{{ $allFilters['translate_to'] }}">
            @endif

            <div class="form-group">
                <label class="sr-only" for="search">{{ trans('terms.index.search.search') }}</label>
                <input type="text" class="form-control" name="search" id="search" 
                    placeholder="{{ trans('terms.index.search.placeholder') }}"
                    value="{{ isset($allFilters['search']) ? urldecode($allFilters['search']) : old('search') }}">      
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default form-control">
                    {{ trans('terms.index.search.search') }}</button>
            </div>
            
            {!! getLocaleInputField() !!}
            
        </form>
    </div>
</div>
