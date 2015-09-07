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
            $translationId = Term::where('term', $input['term'])
                ->whereHas('synonym', function ($query) use ($input) {
                        $query->where('language_id', $input['language_id'])
                              ->where('part_of_speech_id', $input['part_of_speech_id'])
                              ->where('scientific_field_id', $input['scientific_field_id']);
                    })
                ->value('synonym_id');
            
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
}
