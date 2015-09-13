<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ManagesTermsAndSynonyms;
use App\Http\Requests;
use App\Http\Requests\EditTranslationRequest;
use App\Synonym;
use App\Term;
use Illuminate\Support\Facades\Auth;

class SynonymsController extends Controller
{
    use ManagesTermsAndSynonyms;
    
    public function __construct()
    {
        // User has to be authenticated, except for specified methods.
        $this->middleware('auth', ['except' => ['index', 'show']]);
        // Check if user has Administrator role for specified methods.
        $this->middleware('role:1000', ['only' => ['edit', 'update']]);
    }
    
    /**
     * Add translation for the current synonym.
     * 
     * @param EditTranslationRequest $request
     * @param type $slugUnique
     * @return type
     */
    public function addTranslation(Requests\EditTranslationRequest $request, $slugUnique)
    {
        // Get all input from the request. Also prepare input values in case we need to create a new term.
        $input = $this->prepareInputValues($request->all());
        // Get the user suggesting the translation
        $input['user_id'] = Auth::id();
        
        // Get the term to be used to add translation trough synonym relationship.
        $term = Term::where('slug_unique', $slugUnique)->with('synonym', 'synonym.translations')->firstOrFail();
        
        // Make sure that languages are not the same
        if ($term->synonym->language_id == $input['language_id']) {
            return back()->with([
                    'alert' => 'Translated term can not be in the same language',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        
        // Check if the translation term already exist
        if ($this->termExists($input)) {
            // We will get the existing term and use its synonym_id as translation_id
            $translationId = $this->getExistingSynonymId($input);
            
            // Check if the translation for synonyms already exists
            if ($term->synonym->translations->contains($translationId)) {
                return back()->withInput()->with([
                    'alert' => 'This term already exists as translation...',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
                        
            // If no, we can add (suggest) a translation
            $term->synonym->addTranslation($translationId, $input['user_id']);
            return back()->with([
                    'alert' => 'Translation suggested for existing term',
                    'alert_class' => 'alert alert-success'
                ]);
        }
        else {
            // Prepare new synonym 
            $translationSynonym = Synonym::create($input);
            // Persist the new Term
            $translationSynonym->terms()->create($input);
            // Ok, we can suggest the translation.
            $term->synonym->addTranslation($translationSynonym->id, $input['user_id']);
            return back()->with([
                    'alert' => 'New term added and translation suggested',
                    'alert_class' => 'alert alert-success'
                ]);
        }
    }
    
    public function suggestMergeSynonym(Requests\EditTermRequest $request, $slugUnique)
    {
        // Prepare input values in case that the term doesn't exits.
        $input = $this->prepareInputValues($request->all());
        
        // Get the original term with synonym information.
        $term = Term::where('slug_unique', $slugUnique)
                ->with('synonym', 'synonym.translations', 'synonym.mergeSuggestions')->firstOrFail();
        
        // TODO Check if the existing term and suggested synonym have the same lang, field, part...
        // 
        // Check if the suggested term exists in language, field, part of speech.
        if ($this->termExists($input)) {
            $mergeId = $this->getExistingSynonymId($input);
            
            // Make sure that the synonyms are not the same
            if ($term->synonym_id === $mergeId) {
                return back()->with([
                    'alert' => 'Those are already synonyms...',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
            // Check if the suggestion already exist
            $suggestionExists = $term->synonym->mergeSuggestions()
                    ->where('synonym_id', $term->synonym_id)
                    ->where('merge_id', $mergeId)
                    ->exists();
            if ($suggestionExists) {
                return back()->with([
                    'alert' => 'This suggestion already exists...',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
            // We can add a synonym merge suggestion
            Auth::user()->mergeSuggestions()->create([
                'synonym_id' => $term->synonym_id, 'merge_id' => $mergeId
            ]);
            
            return back()->with([
                    'alert' => 'Synonym merge suggested for existing term...',
                    'alert_class' => 'alert alert-success'
                ]);
        }
        else {
            // Set the user_id
            $input['user_id'] = Auth::id();
            // Create new term with the same synonym ID and suggest it
            $term->synonym->terms()->create($input);
            return back()->with([
                    'alert' => 'New synonym suggested...',
                    'alert_class' => 'alert alert-success'
                ]);
        }
        
    }
    
    public function approveMergeSynonym() {
        // associate all the terms from the suggested synoynm to the new synonym.
        // associate all definitions with new synoynm
        // associate all translations with new synonym
    }
    
    public function rejectMergeSynonym() {
        // Set status of the suggestion to 'rejected'
    }
}
