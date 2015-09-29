<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Term;
use App\Repositories\SuggestionsFilterRepository;
use App\Language;
use App\Http\Controllers\Traits\ManagesTerms;
use App\Concept;

class SuggestionsController extends Controller
{

    use ManagesTerms;

    public function index()
    {
        return view('suggestions.index');
    }

    /**
     * Get all suggested terms.
     * @param SuggestionsFilterRepository $filters
     * @return type
     */
    public function terms(SuggestionsFilterRepository $filters)
    {
        $termFilters = $filters->allFilters();

        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();

        $terms = Term::suggested()
                ->where($termFilters)
                ->with('votes')
                ->get();

        return view('suggestions.terms', compact('terms', 'termFilters', 'languages', 'scientificFields'));
    }

    /**
     * Get all merge suggestions
     * TODO Refactor this
     * @param SuggestionsFilterRepository $filters
     * @return type
     */
    public function merges(SuggestionsFilterRepository $filters)
    {
        $termFilters = $filters->allFilters();

        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();

        $suggestedTerms = Term::whereHas('mergeSuggestions', function ($query) {
                    $query->where('status_id', 500);
                })
                ->where($termFilters)
                ->with('language', 'concept', 'concept.terms', 'concept.terms.language', 'mergeSuggestions.concept.terms', 'mergeSuggestions.concept.terms.language')
                ->get();

//        $mergeSuggestions = MergeSuggestion::where('status_id', 500)
//                ->with(['term' => function ($query) use ($termFilters) {
//                    $query->where($termFilters);
//                }
//                    , 'term.language', 'concept',
//                    'concept.terms' => function ($query) {
//                        $query->orderBy('language_id');
//                    }, 'concept.terms.language' ])
//                ->get();

        return view('suggestions.merges', compact('termFilters', 'languages', 'scientificFields', 'suggestedTerms'));
    }

    public function definitions(SuggestionsFilterRepository $filters)
    {
        $termFilters = $filters->allFilters();
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();

        $concepts = Concept::whereHas('definitions', function ($query) {
                    $query->suggested();
                })
                ->whereHas('terms', function($query) use ($termFilters) {
                    $query->where($termFilters);
                })
                ->with(['terms' => function ($query) use ($termFilters) {
                    $query->where($termFilters);
                }, 
                    'terms.status', 'terms.language'
                  ])
                ->get();

        return view('suggestions.definitions', compact('termFilters', 'languages', 'scientificFields', 'concepts'));
    }

}
