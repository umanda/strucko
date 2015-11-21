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

                if((++$counter)%100 == 0) {
                    echo $counter . ' ' . round(abs(time() - $startTime) / 60,2). " minutes\n";
                }
            }
        });
        
    }
}
