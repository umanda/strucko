
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Filters
                </a>
            </h4>
        </div>
        {{-- If language and field is set, check for menu letters --}}
        @if(isset($allFilters['language_id']) && isset($allFilters['scientific_field_id']))
            {{-- If there are no menu letters, show filter --}}
            @if(isset($menuLetters) && $menuLetters->isEmpty())
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">

            {{-- If there are menu letters, do not show filter --}}
            @else
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            @endif

        {{-- No language and field, show filter --}}
        @else
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        @endif
        <div class="panel-body">

            <form method="GET" action="/terms" class="form-horizontal">
                            
                            <div class="form-group">
                                <label for="language_id" class="col-sm-2 control-label">Language:</label>
                                <div class="col-sm-10">
                                    {!! Form::select('language_id', array_merge(['' => 'Choose language'], $languages->lists('ref_name', 'id')->toArray()),
                                    isset($allFilters['language_id']) ? $allFilters['language_id'] : old('language_id'), 
                                    ['id' => 'language_id', 'required' => 'required', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="scientific_field_id" class="col-sm-2 control-label">Field:</label>
                                <div class="col-sm-10">
                                    {!! Form::select('scientific_field_id', array_merge(['' => 'Choose field'], $scientificFields),
                                    isset($allFilters['scientific_field_id']) ? $allFilters['scientific_field_id'] : old('scientific_field_id'), 
                                    ['id' => 'scientific_field_id', 'required' => 'required', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="translate_to" class="col-sm-2 control-label">Translate to:</label>
                                <div class="col-sm-10">
                                    {!! Form::select('translate_to', array_merge(['' => '...optional'], $languages->lists('ref_name', 'id')->toArray()),
                                    isset($allFilters['translate_to']) ? $allFilters['translate_to'] : old('translate_to'), 
                                    ['id' => 'translate_to', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                
                {{-- If menu_letter is set, include it in the form 
                            @if (isset($allFilters['menu_letter']))
                            <input type="hidden" name="menu_letter" value="{{ $allFilters['menu_letter'] }}">
                            @endif --}}
                {{-- If search is set, include it in the form --}}
                            @if (isset($allFilters['search']))
                            <input type="hidden" name="search" value="{{ $allFilters['search'] }}">
                            @endif
                            
                            <div class="form-group">

                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Set filters</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>