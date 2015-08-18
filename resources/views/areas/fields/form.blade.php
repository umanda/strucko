{!! csrf_field() !!}
<div class="form-group">
    <label for="term">Scientific field:</label>
    <input type="text" id="scientific_field" name="scientific_field" maxlength="255" required="required"
           placeholder="Scientific field" class="form-control"
           value="{{ isset($field) ? $field->scientific_field : old('scientific_field') }}">
</div>
<div class="form-group">
    <label for="term">Mark:</label>
    <input type="text" id="mark" name="mark" maxlength="2" required="required"
           placeholder="Mark" class="form-control"
           value="{{ isset($field) ? $field->mark : old('mark') }}">
</div>
<div class="form-group">
    <label for="scientific_area_id">Scientific area:</label>
    <select id="scientific_area_id" name="scientific_area_id" required="required" class="form-control">
        @if (isset($field))
        <option value="{{ $field->scientific_area_id }}" selected="selected">{{ $field->scientificArea->scientific_area }}</option>
        @endif
        @foreach ($areas as $area)
        <option value="{{ $area->id }}">{{ $area->scientific_area }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="term">Description:</label>
    <textarea id="description" name="description" placeholder="Description"
              rows="3" class="form-control">{{ isset($field) ? $field->description : old('description') }}</textarea>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" id="active" name="active"
            @if (isset($field))
                {{ $field->active ? 'checked="checked"' : old('active') }}
            @endif
            > Active?
    </label>
</div>

</div>