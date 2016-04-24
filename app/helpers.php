<?php

/**
 * Set active page class
 *
 * @param string $uri
 * @return string
 */
function set_active($uri)
{
    return \Request::is($uri) ? 'active' : '';
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

/**
 * Get urlencoded() items in provided array.
 * 
 * @param array $items
 * @return array
 */
function getUrlEncodedArray(array $items) {
    
    return array_map(function ($item) {
            return urlencode($item);
        }, $items);
}

/**
 * Prepare URL using the action helper method from Laravel.
 * Also append the locale parameter for localization purposes.
 * 
 * @param string $controller
 * @param array $parameters
 * @return string
 */
function resolveUrlAsAction($controller, array $parameters = []) {
    // Merge the lang array from the session.
    $parameters += \Session::get('locale');
    // UrlDecode the pearameters (we have coded them in the repository)
    $decodedParameters = getUrlDecodedArray($parameters);
    // If you want a quick way to remove NULL, FALSE and Empty Strings (""), 
    // but leave values of 0 (zero), you can use the standard 
    // php function strlen as the callback function:
    $filteredParameters = array_filter($decodedParameters, 'strlen');
    
    if (! empty($filteredParameters)) {
        return action($controller, $filteredParameters);
    }
    // There are no parameters, return without them.
    return action($controller);
}

/**
 * Generate a querystring url for the application.
 * Assumes that you want a URL with a querystring rather than route params
 * (which is what the default url() helper does)
 *
 * source: http://stackoverflow.com/questions/21632835/laravel-route-url-with-query-string
 * 
 * @param  string  $path
 * @param  mixed   $qs
 * @param  bool    $secure
 * @return string
 */
function resolveUrlAsUrl($path = null, $qs = [], $secure = null) {
    $qs += Session::get('locale');
    $url = app('url')->to($path, $secure);
    
    $fullUrl = prepareFullUrlWithQueryString($url, $qs);
    
    return $fullUrl;

}

/**
 * Set locale query parameter in URL. Used for localization.
 * 
 * @param string $locale
 * @return string
 */
function setLocaleInUrl($locale = null) {
    // Get the current url withouth query string.
    $url = \Request::url();
    // If the locale is null, simply remove it from the querystring
    if ($locale == null) {
        $queryString = \Request::except('locale');
    }
    else {
        // We have to set the provided locale
        $queryString = \Request::all();
        $queryString['locale'] = $locale;
    }
    // Now prepare the full url with querystring.
    $fullUrl = prepareFullUrlWithQueryString($url, $queryString);
    
    return $fullUrl;
}

function prepareFullUrlWithQueryString($url, $queryString)
{
    if (count($queryString)){
        foreach($queryString as $key => $value){
            $queryString[$key] = sprintf('%s=%s',$key, urlencode($value));
        }
        $url = sprintf('%s?%s', $url, implode(htmlentities('&'), $queryString));
    }
    
    return $url;
}

/**
 * Used to generate hreflang meta used by Google do indicate 
 * different URL versions for different locale, for the same resource.
 * 
 * @return string
 */
function generateHreflangURIs()
{
    // Define valid locales to generate URLs for.
    $validLocales = ['hr'];
    // Hreflang template string
    $hreflangTemplate = '<link rel="alternate" href="%s" hreflang="%s" />' . PHP_EOL;
    // String to be used for all hreflang links.
    $hreflangURIs = '';
    // Get all query parameters, without locale.
    $queryParams = array_filter(\Request::query(), function($key) {
            return $key != 'locale';
        }, ARRAY_FILTER_USE_KEY);
    // Get current URL, without query parameters.
    $url = \Request::url();
    // Prepare the full URI without the locale parameter.
    $fullUrl = prepareFullUrlWithQueryString($url, $queryParams);
    // First create the URL for the default (en) locale.
    $hreflangURIs = sprintf($hreflangTemplate, 
            $fullUrl, \Config::get('app.fallback_locale', 'en'));
    // Now create all URIs for all other locales in validLocales array.
    foreach ($validLocales as $validLocale) {
        // Again prepare full URI, now including locale.
        $queryParams['locale'] = $validLocale;
        $fullUrl = prepareFullUrlWithQueryString($url, $queryParams);
        $hreflangURIs .= sprintf($hreflangTemplate, 
                $fullUrl, $validLocale);
    }
    return $hreflangURIs;
}

/**
 * If the current request has locale query parameter, generate the hidden
 * input field with that locale as value. This input field can be used in forms.
 * 
 * @return string
 */
function getLocaleInputField()
{
    $locales = ['hr'];
    
    if (\Request::has('locale') && in_array(\Request::get('locale'), $locales)) {
        return '<input type="hidden" name="locale" value="' . \Request::get('locale') . '">';
    }
}