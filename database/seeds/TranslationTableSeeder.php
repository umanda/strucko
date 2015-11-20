<?php

use Illuminate\Database\Seeder;
use App\Term;
use App\Concept;
use App\Translation;

class TranslationTableSeeder extends Seeder
{
    /**
     * I will use this class to populate the new translations table.
     * 
     *
     * @return void
     */
    public function run()
    {
        $startItem = 10000;
        $endItem = 20000;
        
        // Get concepts
        $concepts = Concept::skip($startItem)->take($endItem)->get();
        
        // For each concpets, get each term.
        foreach ($concepts as $concept) {
            // Get terms with that concept
            $termsInConcept = Term::where('concept_id', $concept->id)
                    ->get();
            foreach ($termsInConcept as $termInConcept) {
                // Get translations for current term in concept.
                $translations = Term::where('concept_id', $termInConcept->concept_id)
                    ->where('scientific_field_id', $termInConcept->scientific_field_id)
                    ->where('language_id', '<>', $termInConcept->language_id)
                    ->get();
                // Add translations in both ways.
                foreach ($translations as $translation) {
                    Translation::firstOrCreate([
                        'term_id' => $termInConcept->id,
                        'translation_id' => $translation->id,
                        'user_id' => $translation->user_id
                    ]);
                    Translation::firstOrCreate([
                        'term_id' => $translation->id,
                        'translation_id' => $termInConcept->id,
                        'user_id' => $termInConcept->user_id
                    ]);
                }
            }
        }
        
//        // Get terms.
//        $terms = Term::skip($startItem)->take($endItem)->get();
//        // For each term, get terms with the same concept_id and scientific_field_id, 
//        // but different language_id
//        foreach($terms as $term) {
//            $translations = Term::where('concept_id', $term->concept_id)
//                    ->where('scientific_field_id', $term->scientific_field_id)
//                    ->where('language_id', '<>', $term->language_id)
//                    ->get();
//            // Foreach translation create entry in the translations table.
//            foreach ($translations as $translation) {
//                Translation::firstOrCreate([
//                    'term_id' => $term->id,
//                    'translation_id' => $translation->id,
//                    'user_id' => $translation->user_id
//                ]);
//                        
//            }
//        }
    }
}
