<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\Synonym;
use App\Repositories\SuggestionsTermsFilterRepository;
use App\Language;
use App\ScientificField;
use App\Http\Controllers\Traits\ManagesTermsAndSynonyms;
use DB;

class SuggestionsController extends Controller
{
    use ManagesTermsAndSynonyms;
    
    public function index()
    {
        return view('suggestions.index');
    }
    
    public function terms(SuggestionsTermsFilterRepository $filters)
    {
        $termFilters = $filters->allFilters();
        
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        
        $terms = Term::suggested()
                    ->whereHas('synonym', function ($query) use ($termFilters) {
                        $query->where($termFilters);
                    })
                    ->with('votes')
                    ->get();
                    //dd($terms);
        return view('suggestions.terms', compact('terms', 'termFilters', 'languages', 'scientificFields'));
    }
    
    public function mergeSuggestions(SuggestionsTermsFilterRepository $filters) {
        $synonymFilters = $filters->allFilters();
        
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        
        $synonyms = Synonym::whereHas('mergeSuggestions', function ($query) {
                                $query->where('status_id', 500);
                            })
                            ->where($synonymFilters)
                            ->with('mergeSuggestions','mergeSuggestions.mergedSynonym', 'mergeSuggestions.mergedSynonym.terms', 'terms')
                            ->get();
        return view('suggestions.merge_suggestions', compact('synonymFilters', 'languages', 'scientificFields', 'synonyms') );
    }
}
