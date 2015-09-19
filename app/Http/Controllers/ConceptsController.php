<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Controllers\Traits\ManagesTerms;
use Auth;
use App\Term;
use App\MergeSuggestion;

class ConceptsController extends Controller
{

    use ManagesTerms;

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
            
            // Check if the suggestion already exist
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
        $term->concept->terms()->create($input);

        return back()->with([
                    'alert' => 'New term suggested as synoynm...',
                    'alert_class' => 'alert alert-success'
        ]);
        
    }
}
