/* 
 * Marko Ivančić <cicnavi@gmail.com>
 * 
 * 
 */


$(document).ready(function () {
    // Take care of the alert
    $('div.alert').not('.alert-warning').delay(3000).slideUp(300);

    // Make these elements select2
    $('#language_id, #scientific_field_id, #translate_to, #part_of_speech_id').select2({
        width: "100%"
    });

    // Set appropriate translate_to to disabled if language_id exits on the page
    if ($('#translate_to').length && $('#language_id').length) {
        setTranslateTo();
    }
});

/**
 * Set the appropriate translate_to option to disabled, depending on the 
 * selected language_id. Implemented because we shouldn't be able to select to
 * translate to the same language. Also set the translate_to to the
 * old language_id if the new language_id is the same as the current translate_to.
 * 
 * @returns {undefined}
 */
function setTranslateTo() {
    // Get the current value of the selected language
    var languageId = $('#language_id').val();
    // set all disabled translate_to options to enabled
    $('#translate_to').children('option[disabled]').prop('disabled', false);
    // Find the translate_to with with the same languageId and disable it
    $('#translate_to').children('option[value="' + languageId + '"]').prop('disabled', true);

    // ID of the language_id before change stored in data.
    $('#language_id').data('previous', $('#language_id').val());
    $('#language_id').change(function (e) {
        // Get the previous language ID.
        var previousLanguageId = $(this).data('previous');
        // Temporarily disable select2 on translate_to
        $('#translate_to').select2('destroy');
        // Set disabled translate_to options to enabled
        $('#translate_to').children('option[disabled]').prop('disabled', false);
        // Get teh current value of the translate_to
        var translateToId = $('#translate_to').val();
        // Get the current value of the selected language_id
        languageId = $('#language_id').val();
        // If the current translate_to is the same as new language_id,
        // set the new translate_to to the previousLanguageId
        if (languageId === translateToId) {
            // Remove selection from selected translate_to
            $('#translate_to').children('option[disabled]').prop('selected', false);
            $('#translate_to').children('option[value="' + previousLanguageId + '"]').prop('selected', true);
        }
        // Find the translate_to with with the same languageId and disable it
        $('#translate_to').children('option[value="' + languageId + '"]').prop('disabled', true);
        // Enable select2 again
        $('#translate_to').select2({
            width: "100%"
        });
        // Update data storage for the selected language_id
        $(this).data('previous', $(this).val());
    });
}
;