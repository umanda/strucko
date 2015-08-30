<form method="GET" action="/terms/filter/">

    <select name="language_id">
        <option value="hrv">lang</option>
    </select>
    <select name="scientific_field_id">
        <option value="19">field</option>
    </select>
    <input type="submit">
</form>
<select>
    <option value="" disabled selected>Select your option</option>
    <option value="hurr">Durr</option>
</select>

<select>
    <option value='' disabled selected style='display:none;'>Please Choose</option>
    <option value='0'>Open when powered (most valves do this)</option>
    <option value='1'>Closed when powered, auto-opens when power is cut</option>
</select>

<select id="choice">
    <option value="0" selected="selected">Choose...</option>
    <option value="1">Something</option>
    <option value="2">Something else</option>
    <option value="3">Another choice</option>
</select>

select:required:invalid {
  color: gray;
}
option[value=""][disabled] {
  display: none;
}
option {
  color: black;
}

<select required>
  <option value="" disabled selected>Select something...</option>
  <option value="1">One</option>
  <option value="2">Two</option>
</select>

option[default] {
  display: none;
}

<select>
  <option value="" default selected>Select Your Age</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
</select>