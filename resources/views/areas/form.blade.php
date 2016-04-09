{!! csrf_field() !!}
{!! getLocaleInputField() !!}
<div class="form-group">
    <label for="term">Scientific area:</label>
    <input type="text" id="scientific_area" name="scientific_area" maxlength="255" required="required"
           placeholder="Scientific area" class="form-control"
           value="{{ isset($area) ? $area->scientific_area : old('scientific_area') }}">
</div>
<div class="form-group">
    <label for="mark">Mark:</label>
    <input type="text" id="mark" name="mark" maxlength="2" required="required"
           placeholder="Mark" class="form-control"
           value="{{ isset($area) ? $area->mark : old('mark') }}">
</div>
<div class="form-group">
    <label for="description">Description:</label>
    <textarea id="description" name="description" placeholder="Description"
              rows="3" class="form-control">{{ isset($area) ? $area->description : old('description') }}</textarea>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" id="active" name="active"
            @if (isset($area))
                {{ $area->active ? 'checked="checked"' : old('active') }}
            @endif
            > Active?
    </label>
</div>

</div>