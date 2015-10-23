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
use Auth;
use App\MergeSuggestion;
use App\Definition;

class SuggestionsController extends Controller
{
    use ManagesTerms;
    
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth');
        // Check if user has Administrator role for specified methods.
        //$this->middleware('role:1000', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('role:1000');
    }

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
        
        // Get the suggested terms, and load votes for current user.
        $terms = Term::suggested()
                ->where($termFilters)
                ->with(['votes' => function($query) {
                    $query->where('user_id', Auth::id());
                },
                'user',
                'partOfSpeech'
                ])
                ->orderBy('votes_sum', 'DESC')
                ->paginate();
        
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
        
//        $suggestedTerms = Term::whereHas('mergeSuggestions', function ($query) {
//                    $query->where('status_id', 500);
//                })
//                ->where($termFilters)
//                ->with('language', 'concept', 'concept.terms', 'concept.terms.language', 'mergeSuggestions.concept.terms', 'mergeSuggestions.concept.terms.language')
//                ->get();

        $mergeSuggestions = MergeSuggestion::where('status_id', 500)
                ->whereHas('term', function ($query) use ($termFilters) {
                        $query->greaterThanRejected()
                                ->where($termFilters)
                                ->orderBy('votes_sum');
                })
                ->with(['term' => function ($query) use ($termFilters) {
                        $query->greaterThanRejected()
                                ->where($termFilters)
                                ->orderBy('votes_sum');
                    },
                    'concept',
                    'concept.terms' => function ($query) use ($termFilters) {
                        $query->where($termFilters);
                    },
                    'user',
                    ])
                ->orderBy('votes_sum')
                ->paginate();
            
        return view('suggestions.merges', compact('termFilters', 'languages', 'scientificFields', 'mergeSuggestions'));
    }

    public function definitions(SuggestionsFilterRepository $filters)
    {
        $termFilters = $filters->allFilters();
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();

        if (isset($termFilters['language_id'])) {
            $definitions = Definition::suggested()
                    ->where('language_id', $termFilters['language_id'])
                    ->whereHas('concept.terms', function($query) use ($termFilters) {
                        $query->greaterThanRejected()
                                ->where($termFilters);
                    })
                    ->with(['concept.terms' => function($query) use($termFilters) {
                        $query->greaterThanRejected()
                                ->where($termFilters)
                                ->orderBy('votes_sum', 'DESC');
                        },
                        'concept',
                        'concept.terms.status'
                        ])
                    ->orderBy('votes_sum', 'DESC')
                    ->paginate();
                    
        }
//        $concepts = Concept::whereHas('terms', function($query) use ($termFilters) {
//                        $query->greaterThanRejected()
//                                ->where($termFilters);
//                    })
//                ->whereHas('definitions', function ($query) use ($termFilters) {
//                    $query->suggested();
//                })
//                ->with(['terms' => function ($query) use ($termFilters) {
//                    $query->greaterThanRejected()
//                          ->where($termFilters)
//                          ->orderBy('votes_sum');
//                }, 
//                'definitions',
//                'terms.status',
//                'terms.language'
//                ])
//                ->paginate();

        return view('suggestions.definitions', compact('termFilters', 'languages', 'scientificFields', 'definitions'));
    }

}
