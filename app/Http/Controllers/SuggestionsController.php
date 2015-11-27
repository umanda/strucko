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
use App\Translation;
use App\Synonym;
use App\Definition;
use App\Status;

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
        $termFilters = $filters->termFilters();
        $allFilters = $filters->allFilters();

        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id')->toArray();
        
        //dd([ '' => 'Choose status'] + $statuses);
        
        // Get the suggested terms, and load votes for current user.
        $terms = Term::where($termFilters)
                ->with(['votes' => function($query) {
                    $query->where('user_id', Auth::id());
                },
                'user',
                'partOfSpeech'
                ])
                ->orderBy('votes_sum', 'DESC')
                ->paginate();
        
        return view('suggestions.terms', compact('terms', 'termFilters', 'allFilters', 'languages', 'scientificFields', 'statuses'));
    }

    public function definitions(SuggestionsFilterRepository $filters)
    {
        $definitionFilters = $filters->definitionFilters();
        $termFilters = $filters->termFilters();
        $allFilters = $filters->allFilters();
        
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id')->toArray();
        
        $definitions = Definition::where($definitionFilters)
                ->whereHas('term', function($query) use ($termFilters) {
                    $localTermFilters = [];
                    isset($termFilters['scientific_field_id']) ? $localTermFilters['scientific_field_id'] = $termFilters['scientific_field_id'] : '';
                    
                    $query->where($localTermFilters);
                })
                ->with(['term', 'term.status'])
                ->orderBy('votes_sum', 'DESC')
                ->paginate();

        return view('suggestions.definitions', compact('termFilters', 'allFilters', 'languages', 'scientificFields', 'definitions', 'statuses'));
    }
    
    public function translations(SuggestionsFilterRepository $filters)
    {
        $allFilters = $filters->translationFilters();
        
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id')->toArray();
        
        $translationFilters = [];
        isset($allFilters['status_id']) ? $translationFilters['status_id'] = $allFilters['status_id'] : '';
        
        $translations = Translation::where($translationFilters)
                ->whereHas('term', function ($query) use ($allFilters) {
                    $localTermFilters = [];
                    isset($allFilters['language_id']) ? $localTermFilters['language_id'] = $allFilters['language_id'] : '';
                    isset($allFilters['scientific_field_id']) ? $localTermFilters['scientific_field_id'] = $allFilters['scientific_field_id'] : '';
                    $query->where($localTermFilters);
                })
                ->whereHas('translation', function ($query) use ($allFilters) {
                    $localTranslateToFilters = [];
                    isset($allFilters['translate_to']) ? $localTranslateToFilters['language_id'] = $allFilters['translate_to'] : '';
                    $query->where($localTranslateToFilters);
                })
                ->with(['term', 'translation', 'user', 'status'])
                ->take(15)
                ->get();
                
        return view('suggestions.translations', compact('allFilters', 'languages', 'scientificFields', 'statuses', 'translations'));
    }
    
    public function synonyms(SuggestionsFilterRepository $filters)
    {
        $allFilters = $filters->synonymFilters();
        
        // Prepare languages and fields for filtering
        $languages = Language::active()->orderBy('ref_name')->get();
        $scientificFields = $this->prepareScientificFields();
        $statuses = Status::active()->orderBy('id')->lists('status', 'id')->toArray();
        
        $synonymFilters = [];
        isset($allFilters['status_id']) ? $synonymFilters['status_id'] = $allFilters['status_id'] : '';
        
        $synonyms = Synonym::where($synonymFilters)
                ->whereHas('term', function ($query) use ($allFilters) {
                    $localTermFilters = [];
                    isset($allFilters['language_id']) ? $localTermFilters['language_id'] = $allFilters['language_id'] : '';
                    isset($allFilters['scientific_field_id']) ? $localTermFilters['scientific_field_id'] = $allFilters['scientific_field_id'] : '';
                    $query->where($localTermFilters);
                })
                ->with(['term', 'synonym', 'user', 'status'])
                ->paginate();
                
        return view('suggestions.synonyms', compact('allFilters', 'languages', 'scientificFields', 'statuses', 'synonyms'));
    }

}
