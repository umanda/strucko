<?php

namespace App\Repositories;

use Illuminate\Http\Request;

/**
 * Repository for filters used when searching for suggestion in /suggestions/*. Repo is populated using
 * query parameters when searching for filters. Also, last used filter is put 
 * in session as 'suggestionsTermsFilters'.
 *
 * @author mivancic
 */
class SuggestionsFilterRepository
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
    
    protected $termFilters = [];
    
    protected $definitionFilters = [];
    
    protected $translationFilters = [];
    
    protected $synonymFilters = [];
    
    /**
     * Names of all filters which can be used in queries.
     * 
     * @var array
     */
    protected $allFilterKeys = [
        'language_id',
        'scientific_field_id',
        'translate_to',
        'status_id'
    ];
    
    /**
     * Term filters.
     * 
     * @var array
     */
    protected $termFilterKeys = [
        'language_id',
        'scientific_field_id',
        'status_id'
    ];
    
    /**
     * Definition filters.
     * 
     * @var array
     */
    protected $definitionFilterKeys = [
        'language_id',
        'status_id',
    ];
    
    /**
     * Translation filters.
     * 
     * @var array
     */
    protected $translationFilterKeys = [
        'language_id',
        'scientific_field_id',
        'translate_to',
        'status_id'
    ];
    
    /**
     * Synonym filters.
     * 
     * @var array
     */
    protected $synonymFilterKeys = [
        'language_id',
        'scientific_field_id',
        'status_id'
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
    
    public function termFilters()
    {
        $this->prepareTermFilters($this->request);
        return $this->termFilters;
    }
    
    public function definitionFilters()
    {
        $this->prepareDefinitionFilters($this->request);
        return $this->definitionFilters;
    }
    
    public function translationFilters()
    {
        $this->prepareTranslationFilters($this->request);
        return $this->translationFilters;
    }
    
    public function synonymFilters()
    {
        $this->prepareSynonymFilters($this->request);
        return $this->synonymFilters;
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
        $request->session()->put('suggestionsFilter', $this->allFilters);
        
    }
    
    /**
     * Prepare filters from query parameters in request. Also put it in session.
     * 
     * @param Request $request
     * @return array
     */
    protected function prepareTermFilters($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->termFilterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                $this->termFilters[$filterKey] = $request->get($filterKey);
            }
        }
    }
    
    /**
     * Prepare definition filters.
     * 
     * @param Request $request
     * @return array
     */
    protected function prepareDefinitionFilters($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->definitionFilterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                $this->definitionFilters[$filterKey] = $request->get($filterKey);
            }
        }
    }
    
    protected function prepareTranslationFilters($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->translationFilterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                $this->translationFilters[$filterKey] = $request->get($filterKey);
            }
        }
    }
    
    protected function prepareSynonymFilters($request)
    {        
        // Set filters from $filterKeys.
        foreach ($this->synonymFilterKeys as $filterKey) {
            // If the request has filter key, set filter to that value.
            if($request->has($filterKey)) {
                $this->synonymFilters[$filterKey] = $request->get($filterKey);
            }
        }
    }
}
