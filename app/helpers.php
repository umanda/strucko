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

/**
 * Check if the current key is the same as the last key in the provided collection
 * 
 * @param Illuminate\Support\Collection $collection Array with all of the items in it
 * @param integer|string $currentKey Current array key used in the iterator
 * @return boolean True if it is the last key
 */
function is_last($collection, $currentKey)
{
    // Convert the collection to array
    $array = $collection->toArray();
    // Go to the end of the array
    end($array);
    // Get the key from the last element
    $endKey = key($array);
    // Return true if the current key is the same as the end key on the array.
    return $endKey == $currentKey;
}

function flash($message)
{
    sesssion()->flash('message', $message);
}
/**
 * Get urldecoded() items in provided array.
 * 
 * @param array $items
 * @return array
 */
function getUrlDecodedArray(array $items) {
    
    return array_map(function ($item) {
            return urldecode($item);
        }, $items);
}