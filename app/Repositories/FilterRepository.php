<?php

namespace App\Repositories;

use Illuminate\Http\Request;

/**
 * Repository for filters used when searching for terms. Repo is populated using
 * query parameters when searching for filters. Also, last used filter is put 
 * in session as 'filter'.
 *
 * @author mivancic
 */
class FilterRepository
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
     * Current filters that can be used for term filtering. 
     * Wll be populated with query parameters from request.
     * 
     * @var array
     */
    protected $termFilters = [];

    /**
     * Names of all filters which can be used in queries.
     * 
     * @var array
     */
    protected $allFilterKeys = [
        'language_id',
        'scientific_field_id',
        'menu_letter',
        'search',
        'page',
        'translate_to'
    ];
    
    /**
     * Names of filters which can be used for terms.
     * 
     * @var array
     */
    protected $termFilterKeys = [
        'language_id',
        'scientific_field_id',
        'menu_letter',
        'search',
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
     * Prepare filters which can be used for terms.
     * 
     * @return array
     */
    public function termFilters()
    {
        // Prepare term filters from query parameters in request or in session, or
        // set defaults.
        $this->prepareTermFilters($this->request);
        return $this->termFilters;
    }
    
    /**
     * Check if the language and scientific field is set in query parameter.
     * 
     * @return boolean
     */
    public function isSetLanguageAndField()
    {
        return isset($this->allFilters['language_id']) 
                && isset($this->allFilters['scientific_field_id']);
    }
    
    /**
     * Check if the menu letter is set as query parameter.
     * 
     * @return boolean
     */
    public function isSetMenuLetter()
    {
        return isset($this->allFilters['menu_letter']);
    }
    
    /**
     * Check if the search is set as query parameter.
     * 
     * @return boolean
     */
    public function isSetSearch()
    {
        return isset($this->allFilters['search']);
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
        $request->session()->put('allFilters', $this->allFilters);
        
    }
    
    /**
     * Prepare filters which can be used to search for terms.
     * 
     * @param Request $request
     * @return array
     */
    protected function prepareTermFilters ($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->termFilterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                $this->termFilters[$filterKey] = $request->get($filterKey);
            }
        }
    }
}
