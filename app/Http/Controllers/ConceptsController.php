<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Controllers\Traits\ManagesTerms;
use Auth;
use App\Term;
use App\MergeSuggestion;
use App\Translation;
use App\TranslationVote;
use App\Synonym;
use App\SynonymVote;


class ConceptsController extends Controller
{
    use ManagesTerms;
    
    public function __construct()
    {
        // User has to be authenticated.
        $this->middleware('auth');
        // Check if user has Administrator role, except for specified methods.
        $this->middleware('role:1000', ['except' => ['addTranslation', 'addSynonym',
            'voteForSynonym', 'voteForTranslation']]);
        // Check spam threshold.
        $this->middleware('spam', ['only' => ['addTranslation', 'addSynonym']]);
    }
    
    /**
     * Suggest new translation.
     * 
     * @param \App\Http\Requests\EditTranslationRequest $request
     * @param type $slug
     * @return type
     */
    public function addTranslation(Requests\EditTranslationRequest $request, $slug)
    {
        $input = $request->all();
        
        // Get the existing term to be used to add translation trough translation relationship.
        $term = Term::where('slug', $slug)->with('concept')->firstOrFail();
        // Get the user suggesting the translation
        $input['user_id'] = Auth::id();
        // Set the other required fields in case we need a new term.
        $input['scientific_field_id'] = $term->scientific_field_id;
        $input['part_of_speech_id'] = $term->part_of_speech_id;
        // Get all input from the request. 
        // Also prepare input values in case we need to create a new term.
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
            // Check if it's already a translation.
            // We will get the existing term first.
            $translationTerm = Term::where('term', $input['term'])
                    ->where('language_id', $input['language_id'])
                    ->where('part_of_speech_id', $input['part_of_speech_id'])
                    ->where('scientific_field_id', $input['scientific_field_id'])
                    ->first();
            // Try to get a translation.
            $translation = Translation::where('term_id', $term->id)
                    ->where('translation_id', $translationTerm->id)
                    ->with('status')
                    ->first();
            if ( ! is_null($translation)) {
                return back()->with([
                            'alert' => 'This was already ' . strtolower($translation->status->status) . '...',
                            'alert_class' => 'alert alert-warning'
                ]);
            }
            
            // Translation doesn't exist, so create it in both ways.
            $this->createTranslation($term, $translationTerm);
            
            // Translation was suggested
            return back()->with([
                    'alert' => 'Existing term suggested as translation...',
                    'alert_class' => 'alert alert-success'
                ]);
            
        } // End if term exists

        // The term doesn't exist, so we will create a new one and create
        // a translation with it.
        $newTerm = $term->concept->terms()->create($input);
        
        $this->createTranslation($term, $newTerm);
        return back()->with([
                    'alert' => 'New term suggested as translation...',
                    'alert_class' => 'alert alert-success'
        ]);
    }
    
    /**
     * Suggest new synonym.
     * 
     * @param \App\Http\Requests\EditTermRequest $request
     * @param type $slug
     * @return type
     */
    public function addSynonym(Requests\EditTermRequest $request, $slug)
    {
        $input = $request->all();
        
        // Get the existing term to be used to add synonym trough synonym relationship.
        $term = Term::where('slug', $slug)->with('concept')->firstOrFail();
        // Get the user suggesting the synonym
        $input['user_id'] = Auth::id();
        // Set the other required fields in case we need a new term and make
        // sure they are the same as original term.
        $input['language_id'] = $term->language_id;
        $input['scientific_field_id'] = $term->scientific_field_id;
        $input['part_of_speech_id'] = $term->part_of_speech_id;
        // Also prepare input values in case we need to create a new term.
        $input = $this->prepareInputValues($input);
        
        if ($this->termExists($input)) {
            // We will get the existing term first.
            $synonymTerm = Term::where('term', $input['term'])
                    ->where('language_id', $input['language_id'])
                    ->where('part_of_speech_id', $input['part_of_speech_id'])
                    ->where('scientific_field_id', $input['scientific_field_id'])
                    ->first();
            // Check if we got the same term.
            if ($term->id === $synonymTerm->id) {
                return back()->with([
                            'alert' => 'This is the same term...',
                            'alert_class' => 'alert alert-warning'
                ]);
            }
            // Try to get a synonym.
            $synonym = Synonym::where('term_id', $term->id)
                    ->where('synonym_id', $synonymTerm->id)
                    ->with('status')
                    ->first();
            if ( ! is_null($synonym)) {
                return back()->with([
                            'alert' => 'This was already ' . strtolower($synonym->status->status) . '...',
                            'alert_class' => 'alert alert-warning'
                ]);
            }
            
            // Synonym doesn't exist, so create it in both ways.
            $this->createSynonym($term, $synonymTerm);
            
            // Synonym was suggested
            return back()->with([
                    'alert' => 'Existing term suggested as synonym...',
                    'alert_class' => 'alert alert-success'
                ]);
            
        } // End if term exists

        // The term doesn't exist, so we will create a new one and create
        // a translation with it.
        $newTerm = $term->concept->terms()->create($input);
        
        $this->createSynonym($term, $newTerm);
        return back()->with([
                    'alert' => 'New term suggested as synonym...',
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
        // Get the synonym, but make sure that it has the same PoS and SF and 
        // language, but different id from the $term.
        $synonymTerm = Term::where('slug', $input['synonym_slug'])
                ->where('part_of_speech_id', $term->part_of_speech_id)
                ->where('scientific_field_id', $term->scientific_field_id)
                ->where('language_id', $term->language_id)
                ->where('id', '<>', $term->id)
                ->firstOrFail();
        // Get the synonym so I can check if the user already voted
        $synonym1 = Synonym::where('term_id', $term->id)
                ->where('synonym_id', $synonymTerm->id)
                ->with(['votes' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                ->firstOrFail();
        // Get the synonym in the oposite way
        $synonym2 = Synonym::where('term_id', $synonymTerm->id)
                ->where('synonym_id', $term->id)
                ->with(['votes' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                ->firstOrFail();
        // If user voted, return
        if (! $synonym1->votes->isEmpty()){
            return back()->with([
                    'alert' => 'You have already voted for this synonym...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        // Prepare is_positive value.
        $isPositive = isset($input['is_positive']) ? true : false;
        
        // Prepare vote based on user role and its weight,
        // and make it positive or negative based on up or down type of vote.
        if ($isPositive) {
            $vote = Auth::user()->role->vote_weight;
        } 
        else {
            $vote = Auth::user()->role->vote_weight * (-1);
        }
        // Vote in both ways.
        SynonymVote::create([
            'synonym_id' => $synonym1->id,
            'user_id' => Auth::id(),
            'is_positive' => $isPositive
        ]);
        SynonymVote::create([
            'synonym_id' => $synonym2->id,
            'user_id' => Auth::id(),
            'is_positive' => $isPositive
        ]);
        // Add vote to vote_sum on both synonyms.
        $synonym1->increment('votes_sum', $vote);
        $synonym2->increment('votes_sum', $vote);
        
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
        // Get the translation, but make sure that it has the same PoS and SF but 
        // different language.
        $translationTerm = Term::where('slug', $input['translation_slug'])
                ->where('part_of_speech_id', $term->part_of_speech_id)
                ->where('scientific_field_id', $term->scientific_field_id)
                ->where('language_id', '<>', $term->language_id)
                ->firstOrFail();
        // Get the translation so I can check if the user already voted
        $translation1 = Translation::where('term_id', $term->id)
                ->where('translation_id', $translationTerm->id)
                ->with(['votes' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                ->firstOrFail();
        // Get the translation in the oposite way
        $translation2 = Translation::where('term_id', $translationTerm->id)
                ->where('translation_id', $term->id)
                ->with(['votes' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                ->firstOrFail();
        // If user voted, return
        if (! $translation1->votes->isEmpty()){
            return back()->with([
                    'alert' => 'You have already voted for this translation...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        // Prepare is_positive value.
        $isPositive = isset($input['is_positive']) ? true : false;
        
        // Prepare vote based on user role and its weight,
        // and make it positive or negative based on up or down type of vote.
        if ($isPositive) {
            $vote = Auth::user()->role->vote_weight;
        } 
        else {
            $vote = Auth::user()->role->vote_weight * (-1);
        }
        
        // Vote in both ways.
        TranslationVote::create([
            'translation_id' => $translation1->id,
            'user_id' => Auth::id(),
            'is_positive' => $isPositive
        ]);
        TranslationVote::create([
            'translation_id' => $translation2->id,
            'user_id' => Auth::id(),
            'is_positive' => $isPositive
        ]);
        // Add vote to vote_sum on both translations.
        $translation1->increment('votes_sum', $vote);
        $translation2->increment('votes_sum', $vote);
        
        return back()->with([
                    'alert' => 'Vote stored!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    public function approveTranslation($id)
    {
        $translation = Translation::findOrFail($id);
        
        $firstFilter = ['term_id' => $translation->term_id, 'translation_id' => $translation->translation_id];
        $oppositeFilter = ['term_id' => $translation->translation_id, 'translation_id' => $translation->term_id];
        
        Translation::where($firstFilter)
                ->orWhere($oppositeFilter)
                ->update(['status_id' => 1000]);
        
        return back()->with([
                    'alert' => 'Translation approved!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    public function rejectTranslation($id)
    {
        $translation = Translation::findOrFail($id);
        
        $firstFilter = ['term_id' => $translation->term_id, 'translation_id' => $translation->translation_id];
        $oppositeFilter = ['term_id' => $translation->translation_id, 'translation_id' => $translation->term_id];
        
        Translation::where($firstFilter)
                ->orWhere($oppositeFilter)
                ->update(['status_id' => 250]);
        
        return back()->with([
                    'alert' => 'Translation rejected!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    public function approveSynonym($id)
    {
        $synonym = Synonym::findOrFail($id);
        
        $firstFilter = ['term_id' => $synonym->term_id, 'synonym_id' => $synonym->synonym_id];
        $oppositeFilter = ['term_id' => $synonym->synonym_id, 'synonym_id' => $synonym->term_id];
        
        Synonym::where($firstFilter)
                ->orWhere($oppositeFilter)
                ->update(['status_id' => 1000]);
        
        return back()->with([
                    'alert' => 'Synonym approved!',
                    'alert_class' => 'alert alert-success'
                ]);
    }
    
    public function rejectSynonym($id)
    {
        $synonym = Synonym::findOrFail($id);
        
        $firstFilter = ['term_id' => $synonym->term_id, 'synonym_id' => $synonym->synonym_id];
        $oppositeFilter = ['term_id' => $synonym->synonym_id, 'synonym_id' => $synonym->term_id];
        
        Synonym::where($firstFilter)
                ->orWhere($oppositeFilter)
                ->update(['status_id' => 250]);
        
        return back()->with([
                    'alert' => 'Synonym rejected!',
                    'alert_class' => 'alert alert-success'
                ]);
    }

    /**
     * Persist the translation in the translatios table.
     * 
     * @param type $term
     * @param type $translationTerm
     */
    protected function createTranslation($term, $translationTerm)
    {
        Translation::create([
                'term_id' => $term->id,
                'translation_id' => $translationTerm->id,
                'user_id' => Auth::id()
            ]);
        Translation::create([
                'term_id' => $translationTerm->id,
                'translation_id' => $term->id,
                'user_id' => Auth::id()
            ]);
    }
    
    /**
     * Persist synonym in the synonyms table.
     * 
     * @param type $term
     * @param type $synonymTerm
     */
    protected function createSynonym($term, $synonymTerm)
    {
        Synonym::create([
                'term_id' => $term->id,
                'synonym_id' => $synonymTerm->id,
                'user_id' => Auth::id()
            ]);
        Synonym::create([
                'term_id' => $synonymTerm->id,
                'synonym_id' => $term->id,
                'user_id' => Auth::id()
            ]);
    }

}
