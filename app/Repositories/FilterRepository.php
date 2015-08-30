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
     * Current filters. Will be populated with query parameters from request.
     * 
     * @var array
     */
    protected $filters = [];

    /**
     * Names of filters which can be used in queries.
     * 
     * @var array
     */
    protected $filterKeys = [
        'language_id',
        'scientific_field_id',
        'menu_letter',
        'search',
        'page',
        'translate_to'
    ];
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function all()
    {
        // Prepare filters from query parameters in request or in session, or
        // set defaults.
        $this->prepareFilters($this->request);
        return $this->filters;
    }
    
    /**
     * Prepare filters from query parameters in request or in session, or set
     * defaults.
     * 
     * @param Request $request
     * @return array
     */
    protected function prepareFilters($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->filterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                
                $this->filters[$filterKey] = $request->get($filterKey);
                
            }
            
        }
        
        // Also put the values in the session.
        $request->session()->put('filters', $this->filters);
        
    }
}
