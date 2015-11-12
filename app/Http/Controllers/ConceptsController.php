<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Controllers\Traits\ManagesTerms;
use Auth;
use App\Term;
use App\MergeSuggestion;
use App\SynonymVote;
use App\TranslationVote;

class ConceptsController extends Controller
{
    use ManagesTerms;
    
    public function __construct()
    {
        // User has to be authenticated.
        $this->middleware('auth');
    }

    public function addTranslation(Requests\EditTranslationRequest $request, $slug)
    {
        $input = $request->all();
        
        // Get the existing term to be used to add translation trough concept relationship.
        $term = Term::where('slug', $slug)->with('concept')->firstOrFail();
        // Get the user suggesting the translation
        $input['user_id'] = Auth::id();
        // Set the other required fields in case we need a new term.
        $input['scientific_field_id'] = $term->scientific_field_id;
        $input['part_of_speech_id'] = $term->part_of_speech_id;
        // Get all input from the request. Also prepare input values in case we need to create a new term.
        $input = $this->prepareInputValues($input);
        
        // Make sure that languages are not the same
        if ($term->language_id == $input['language_id']) {
            return back()->with([
                        'alert' => 'Translated term can not be in the same language',
                        'alert_class' => 'alert alert-warning'
            ]);
        }

        // If the term exists, suggest to merge it to Concept
        if ($this->termExists($input)) {
            // Check if concept_id is different to check if it's already a translation.
            // We will get the existing term first.
            $translationTerm = Term::where('term', $input['term'])
                    ->where('language_id', $input['language_id'])
                    ->where('part_of_speech_id', $input['part_of_speech_id'])
                    ->where('scientific_field_id', $input['scientific_field_id'])
                    ->first();

            if ($term->concept_id == $translationTerm->concept_id) {
                return back()->with([
                            'alert' => 'This term is already a translation...',
                            'alert_class' => 'alert alert-warning'
                ]);
            }

            // Concept_id is different, so suggest merge...
            // Check if the suggestion already exists
            $mergeSuggestion = MergeSuggestion::where('term_id', $translationTerm->id)
                    ->where('concept_id', $term->concept_id)
                    ->with('status')
                    ->first();
            // Suggest the merge if it doesn't exist.
            if (is_null($mergeSuggestion)) {
                Auth::user()->mergeSuggestions()
                        ->create(['term_id' => $translationTerm->id, 'concept_id' => $term->concept_id]);
                return back()->with([
                            'alert' => 'The term already exists and we have made a merge suggestion...',
                            'alert_class' => 'alert alert-success'
                ]);
            }

            // The suggestion already exists so output the status of the suggestion.
            return back()->with([
                        'alert' => 'This was already ' . strtolower($mergeSuggestion->status->status) . '...',
                        'alert_class' => 'alert alert-warning'
            ]);

        }

        // The term doesn't exist, so we will simply suggest it as a new term 
        // with the same concept_id.
        $term->concept->terms()->create($input);

        return back()->with([
                    'alert' => 'New term added as translation...',
                    'alert_class' => 'alert alert-success'
        ]);
    }

    public function addSynonym(Requests\EditTermRequest $request, $slug)
    {
        $input = $request->all();
        
        // Get the existing term to be used to add translation trough concept relationship.
        $term = Term::where('slug', $slug)->with('concept')->firstOrFail();
        // Get the user suggesting the translation
        $input['user_id'] = Auth::id();
        // Set the other required fields in case we need a new term.
        $input['language_id'] = $term->language_id;
        $input['scientific_field_id'] = $term->scientific_field_id;
        $input['part_of_speech_id'] = $term->part_of_speech_id;
        // Get all input from the request. Also prepare input values in case we need to create a new term.
        $input = $this->prepareInputValues($input);
         
        // Check if the suggested term exists in language, field, part of speech.
        if ($this->termExists($input)) {
            $synonymTerm = Term::where('term', $input['term'])
                    ->where('language_id', $input['language_id'])
                    ->where('part_of_speech_id', $input['part_of_speech_id'])
                    ->where('scientific_field_id', $input['scientific_field_id'])
                    ->first();
            
            // Make sure that the suggestion is not the same term.
            if ($term->id === $synonymTerm->id) {
                return back()->with([
                    'alert' => 'This is the same term...',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
            
            // Make sure that they have different concepts.
            if ($term->concept_id === $synonymTerm->concept_id) {
                return back()->with([
                    'alert' => 'This is already a synonym...',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
            
            // Check if the merge suggestion already exist
            $mergeSuggestion = MergeSuggestion::where('term_id', $synonymTerm->id)
                    ->where('concept_id', $term->concept_id)
                    ->with('status')
                    ->first();
            // Suggest the merge if it doesn't exist.
            if (is_null($mergeSuggestion)) {
                Auth::user()->mergeSuggestions()
                        ->create(['term_id' => $synonymTerm->id, 'concept_id' => $term->concept_id]);
                return back()->with([
                            'alert' => 'The term already exists and we have made a merge suggestion...',
                            'alert_class' => 'alert alert-success'
                ]);
            }

            // The merge suggestion already exists so output the status of the suggestion.
            return back()->with([
                        'alert' => 'This was already ' . strtolower($mergeSuggestion->status->status) . '...',
                        'alert_class' => 'alert alert-warning'
            ]);
            
        }
        
        // The term doesn't exist, we can create it with the same concept_id
        // trough relationship.
        $term->concept->terms()->create($input);

        return back()->with([
                    'alert' => 'New term suggested as synoynm...',
                    'alert_class' => 'alert alert-success'
        ]);
        
    }
    /**
     * Vote for synonym up or down.
     * 
     * @param \App\Http\Requests\SynonymVoteRequest $request
     */
    public function voteForSynonym(Requests\SynonymVoteRequest $request, $slug)
    {
        $input = $request->all();    
        
        // Try to get the term and synonym from slugs.
        $term = Term::where('slug', $slug)->firstOrFail();
        // Get the synonym, but make sure that it has the same concept and language
        // and different ID.
        $synonym = Term::where('slug', $input['synonym_slug'])
                ->where('concept_id', $term->concept_id)
                ->where('language_id', $term->language_id)
                ->whereNotIn('id', [$term->id])
                ->firstOrFail();
        
        // Prepare is_positive value.
        $isPositive = isset($input['is_positive']) ? 1 : -1;
        
        // Prepare vote based on user role and its weight,
        // and make it positive or negative based on up or down type of vote.
        $vote = Auth::user()->role->vote_weight * $isPositive;
        
        // Make sure that the user didn't already vote.
        $exists = SynonymVote::where('term_id', $term->id)
                ->where('synonym_id', $synonym->id)
                ->where('user_id', Auth::id())
                ->exists();
        
        if($exists){
            return back()->with([
                    'alert' => 'You have already voted for this synonym...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        // Vote in both ways.
        SynonymVote::create([
            'term_id' => $term->id,
            'synonym_id' => $synonym->id,
            'user_id' => Auth::id(),
            'vote' => $vote
        ]);
        SynonymVote::create([
            'term_id' => $synonym->id,
            'synonym_id' => $term->id,
            'user_id' => Auth::id(),
            'vote' => $vote
        ]);
        return back()->with([
                    'alert' => 'Vote stored!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
     /**
     * Vote for translation up or down.
     * 
     * @param \App\Http\Requests\TranslationVoteRequest $request
     */
    public function voteForTranslation(Requests\TranslationVoteRequest $request, $slug)
    {
        $input = $request->all();    
        
        // Try to get the term and translation from slugs.
        $term = Term::where('slug', $slug)->firstOrFail();
        // Get the translation, but make sure that it has the same concept and 
        // different language.
        $translation = Term::where('slug', $input['translation_slug'])
                ->where('concept_id', $term->concept_id)
                ->where('language_id', '<>', $term->language_id)
                ->firstOrFail();
        
        // Prepare is_positive value.
        $isPositive = isset($input['is_positive']) ? 1 : -1;
        
        // Prepare vote based on user role and its weight,
        // and make it positive or negative based on up or down type of vote.
        $vote = Auth::user()->role->vote_weight * $isPositive;
        
        // Make sure that the user didn't already vote.
        $exists = TranslationVote::where('term_id', $term->id)
                ->where('translation_id', $translation->id)
                ->where('user_id', Auth::id())
                ->exists();
        
        if($exists){
            return back()->with([
                    'alert' => 'You have already voted for this translation...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        // Vote in both ways.
        TranslationVote::create([
            'term_id' => $term->id,
            'translation_id' => $translation->id,
            'user_id' => Auth::id(),
            'vote' => $vote
        ]);
        TranslationVote::create([
            'term_id' => $translation->id,
            'translation_id' => $term->id,
            'user_id' => Auth::id(),
            'vote' => $vote
        ]);
        return back()->with([
                    'alert' => 'Vote stored!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    

    public function detachTerm()
    {
        
    }
}
