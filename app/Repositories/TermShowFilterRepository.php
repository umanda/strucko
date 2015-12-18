<?php

namespace App\Repositories;

use Illuminate\Http\Request;

/**
 * Repository for filters used when showing specific term on /terms/{slug}.
 * Repo is populated using query parameters. Also, last used filter is put 
 * in session as 'suggestionsTermsFilters'.
 *
 * @author mivancic
 */
class TermShowFilterRepository
{
    /**
     * Current request
     * 
     * @var Request
     */
    protected $request;
    
    /**
     * All current filters. Will be populated with query parameters from request.
     * 
     * @var array
     */
    protected $allFilters = [];
    
    /**
     * Names of all filters which can be used in queries.
     * 
     * @var array
     */
    protected $allFilterKeys = [
        'translate_to',
    ];
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Return all filters as an array.
     * 
     * @return array
     */
    public function allFilters()
    {
        // Prepare filters from query parameters in request or in session, or
        // set defaults.
        $this->prepareAllFilters($this->request);
        return $this->allFilters;
    }
    
    /**
     * Check if the language and scientific field is set in query parameter.
     * 
     * @return boolean
     */
    public function isSetTranslateTo()
    {
        return isset($this->allFilters['translate_to']);
    }
    
    /**
     * Prepare filters from query parameters in request. Also put it in session.
     * 
     * @param Request $request
     * @return array
     */
    protected function prepareAllFilters($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->allFilterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                $this->allFilters[$filterKey] = $request->get($filterKey);
            }
        }
        
        // Also put the values in the session.
        $request->session()->put('termShowFilters', $this->allFilters);
        // Update the allFilters session key with the translate_to value.
        
        if(array_key_exists('translate_to', $this->allFilters)) {
            $request->session()->put('allFilters.translate_to', $this->allFilters['translate_to']);
        }
    }
}
