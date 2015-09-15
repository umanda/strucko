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
                    ->with(['votes' => function ($query) {
                        $query->select(DB::raw('SUM(vote) as vote_sum'));
                    }])
                    ->get();
                    //dd($terms);
        return view('suggestions.terms', compact('terms', 'termFilters', 'languages', 'scientificFields'));
    }
}
