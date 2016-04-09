{!! csrf_field() !!}
{!! getLocaleInputField() !!}
<div class="form-group">
    <label for="ref_name">Language name:</label>
    <input type="text" id="ref_name" name="ref_name" maxlength="255" required="required"
           placeholder="Language reference name in english" class="form-control"
           value="{{ isset($language) ? $language->ref_name : old('ref_name') }}">
</div>
<div class="form-group">
    <label for="id">Language ID:</label>
    <input type="text" id="id" name="id" maxlength="3" required="required"
           placeholder="Language ID" class="form-control"
           value="{{ isset($language) ? $language->id : old('id') }}">
</div>
<div class="form-group">
    <label for="locale">Locale:</label>
    <input type="text" id="locale" name="locale" maxlength="255"
           placeholder="Locale" class="form-control"
           value="{{ isset($language) ? $language->locale : old('locale') }}">
</div>
<div class="form-group">
    <label for="part2b">Part 2b:</label>
    <input type="text" id="part2b" name="part2b" maxlength="3"
           placeholder="Part 2b" class="form-control"
           value="{{ isset($language) ? $language->part2b : old('part2b') }}">
</div>
<div class="form-group">
    <label for="part2t">Part 2t:</label>
    <input type="text" id="part2t" name="part2t" maxlength="3"
           placeholder="Part 2t" class="form-control"
           value="{{ isset($language) ? $language->part2t : old('part2t') }}">
</div>
<div class="form-group">
    <label for="part1">Part 1:</label>
    <input type="text" id="part1" name="part1" maxlength="2"
           placeholder="Part 1" class="form-control"
           value="{{ isset($language) ? $language->part1 : old('part1') }}">
</div>
<div class="form-group">
    <label for="scope">Scope:</label>
    <input type="text" id="scope" name="scope" maxlength="1" required="required"
           placeholder="Scope" class="form-control"
           value="{{ isset($language) ? $language->scope : old('scope') }}">
    <span id="scopeHelp" class="help-block">
        I(ndividual), M(acrolanguage), S(pecial)
    </span>
</div>
<div class="form-group">
    <label for="type">Type:</label>
    <input type="text" id="type" name="type" maxlength="1" required="required"
           placeholder="Type" class="form-control"
           value="{{ isset($language) ? $language->type : old('type') }}">
    <span id="scopeHelp" class="help-block">
        A(ncient), C(onstructed), E(xtinct), H(istorical), L(iving), S(pecial)
    </span>
</div>
<div class="form-group">
    <label for="comment">Comment:</label>
    <input type="text" id="comment" name="comment" maxlength="255"
           placeholder="Comment" class="form-control"
           value="{{ isset($language) ? $language->comment : old('comment') }}">
</div>


<div class="checkbox">
    <label>
        <input type="checkbox" id="active" name="active"
            @if (isset($language))
                {{ $language->active ? 'checked="checked"' : old('active') }}
            @endif
            > Active?
    </label>
</div>

</div>