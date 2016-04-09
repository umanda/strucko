
{!! csrf_field() !!}
{!! getLocaleInputField() !!}

<div class="form-group">
    <label for="definition">
        {{ trans('terms.suggestions.definition') }}
        ({{ trans('languages.' . str_replace(' ', '_', $term->language->ref_name)) }})
    </label>
    <textarea id="definition" name="definition" 
              placeholder="{{ trans('terms.suggestions.definition') }}"
              aria-describedby="definitionHelp" rows="3" class="form-control">{{ old('definition') }}</textarea>
    <span id="definitionHelp" class="help-block">
        {{ trans('terms.suggestions.definitionhelp') }}
    </span>
</div>

<div class="form-group">
    <label for="source">
        {{ trans('terms.suggestions.definitionsource') }}
    </label>
    <textarea id="source" name="source" 
              placeholder="{{ trans('terms.suggestions.definitionsourceplaceholder') }}"
              aria-describedby="sourceHelp" rows="2"
              class="form-control">{{ old('source') }}</textarea>
    <span id="sourceHelp" class="help-block">
        {{ trans('terms.suggestions.definitionsourcehelp') }}
    </span>
</div>
<div class="form-group">
    <label for="link">{{ trans('terms.suggestions.linktosource') }}</label>
    <input type="url" id="link" name="link" 
           placeholder="{{ trans('terms.suggestions.linktosourceplaceholder') }}"
           aria-describedby="linkHelp" class="form-control" value="{{ old('link') }}">
    <span id="linkHelp" class="help-block">
        {{ trans('terms.suggestions.linktosourcehelp') }}
    </span>
</div>

<input type="hidden" name="concept_id" 
       value="{{ isset($term) ? $term->concept_id : old('concept_id') }}">

<input type="hidden" name="term_id" 
       value="{{ isset($term) ? $term->id : old('term_id') }}">

<input type="hidden" name="language_id"
       value="{{ isset($term) ? $term->language_id : old('language_id') }}">
