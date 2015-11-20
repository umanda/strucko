<?php

use Illuminate\Database\Seeder;
use App\Term;
use App\Concept;
use App\Synonym;
class SynonymTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startItem = 400;
        $endItem = 15000;
        
        // Get concepts
        $concepts = Concept::skip($startItem)->take($endItem)->get();
        
        // For each concpets, get each term.
        foreach ($concepts as $concept) {
            // Get terms with that concept
            $termsInConcept = Term::where('concept_id', $concept->id)
                    ->get();
            foreach ($termsInConcept as $termInConcept) {
                // Get synonyms for current term in concept.
                $synonyms = Term::where('concept_id', $termInConcept->concept_id)
                    ->where('scientific_field_id', $termInConcept->scientific_field_id)
                    ->where('part_of_speech_id', $termInConcept->part_of_speech_id)
                    ->where('language_id', $termInConcept->language_id)
                    ->where('id', '<>', $termInConcept->id)
                    ->get();
                // Add synonym in both ways.
                foreach ($synonyms as $synonym) {
                    Synonym::firstOrCreate([
                        'term_id' => $termInConcept->id,
                        'synonym_id' => $synonym->id,
                        'user_id' => $synonym->user_id
                    ]);
                    Synonym::firstOrCreate([
                        'term_id' => $synonym->id,
                        'synonym_id' => $termInConcept->id,
                        'user_id' => $termInConcept->user_id
                    ]);
                }
            }
        }
    }
}
