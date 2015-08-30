<form method="GET" action="/terms">
    <input type="hidden" name="language_id" value="{{ $filters['language_id'] }}">
    <input type="hidden" name="scientific_field_id" value="{{ $filters['scientific_field_id'] }}">
    <input type="text" name="search" placeholder="or search for specific term">
    <input type="submit" value="Search">
</form>