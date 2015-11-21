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
        $counter = 0;
        $startTime = time();
        // Get concepts
        Concept::chunk(200, function ($concepts) use (&$counter, $startTime) {
            // For each concpets, get each term.
            foreach ($concepts as $concept) {
                // Get terms with that concept
                $termsInConcept = Term::where('concept_id', $concept->id)
                        ->get();
                foreach ($termsInConcept as $termInConcept) {
                    // Get translations for current term in concept.
                    $translations = Term::where('concept_id', $termInConcept->concept_id)
                        ->where('scientific_field_id', $termInConcept->scientific_field_id)
                        ->where('part_of_speech_id', $termInConcept->part_of_speech_id)
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
                            'user_id' => $translation->user_id
                        ]);
                    }
                }

                if((++$counter)%100 == 0) {
                    echo $counter . ' ' . round(abs(time() - $startTime) / 60,2). " minutes\n";
                }
            }
        });
                
    }
}
