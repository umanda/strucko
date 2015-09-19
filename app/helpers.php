<?php

/**
 * Set active page class
 *
 * @param string $uri
 * @return string
 */
function set_active($uri)
{
    return Request::is($uri) ? 'active' : '';
}

/**
 * Get null if the string from the form input is actually empty. 
 * If it is not empty, it will return its value.
 * 
 * @param string $input Value to check if it is empty or not
 * @return string|null String if not empty, else null
 */
function getNullForOptionalInput($input)
{
    return empty(trim($input)) ? null : $input;
}

/**
 * Set the appropriate warning label for the status.
 * 
 * @param string $status
 * @return string
 */
function status_warning($status) {
    return '<span class="label label-warning">' . $status . '</span>';
}