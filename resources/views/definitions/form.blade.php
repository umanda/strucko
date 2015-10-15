
{!! csrf_field() !!}

<div class="form-group">
    <label class="sr-only" for="definition">Definition</label>
    <textarea id="definition" name="definition" 
              placeholder="Definition {{ isset($term) ? 'in ' . $term->language->ref_name . ' language': '' }}"
              rows="3" class="form-control">{{ old('definition') }}</textarea>
</div>

<div class="form-group">
    <label class="sr-only" for="source">Source (if quoting)</label>
    <textarea id="source" name="source" placeholder="Source of the definition (if quoting)"
              aria-describedby="sourceHelp" rows="2"
              class="form-control">{{ old('source') }}</textarea>
    <span id="sourceHelp" class="help-block">
        If quoting, you are required to indicate the source from which you quoted 
        the definition. If you don't specify the source, we will assume that you are 
        the author of the definition.
    </span>
</div>
<div class="form-group">
    <label class="sr-only" for="link">Link to source</label>
    <input type="url" id="link" name="link" placeholder="Link to soruce (if available)"
           aria-describedby="linkHelp" class="form-control" value="{{ old('link') }}">
    <span id="linkHelp" class="help-block">
        If the source is available online, provide the link to the source here.
    </span>
</div>

<input type="hidden" name="concept_id" 
       value="{{ isset($term) ? $term->concept_id : old('concept_id') }}">

<input type="hidden" name="language_id" 
       value="{{ isset($term) ? $term->language_id : old('language_id') }}">
