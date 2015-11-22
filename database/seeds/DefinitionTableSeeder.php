<?php

use Illuminate\Database\Seeder;
use App\Definition;

class DefinitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dummy data
        //factory(App\Definition::class, 5)->create();
        
        $counter = 0;
        $startTime = time();
        // Get definitions with their terms
        Definition::chunk(200, function ($definitions) use (&$counter, $startTime) {
            $definitions->load('concept.terms');
            // For each concpets, get each term.
            foreach ($definitions as $definition) {
                // Get the number of terms.
                $numberOfTerms = $definition->concept->terms->count();

                // Check if number of terms is less then 1.
                if ($numberOfTerms < 1) {
                    dd($definition);
                }
                // If there is only one term, definition belongs to it.
                if ($numberOfTerms == 1) {
                    $term = $definition->concept->terms->first();
                    $definition->update(['term_id' => $term->id]);
                }
                if ($numberOfTerms > 1) {
                    // Use this to check if we are on the first term.
                    $counterForTerms = 1;
                    foreach ($definition->concept->terms as $term) {
                        // If this is the first term, existing definition belongs to it.
                        if ($counterForTerms == 1) {
                            $definition->update(['term_id' => $term->id]);
                        }
                        // If not the first term, create new definition for each term.
                        if ($counterForTerms > 1) {
                            $replica = $definition->replicate();
                            $replica->term_id = $term->id;
                            $replica->save();
                        }
                        $counterForTerms++;
                    }
                }

                if((++$counter)%100 == 0) {
                    echo $counter . ' ' . round(abs(time() - $startTime) / 60,2). " minutes\n";
                }
            }
        });
        
        
    }
}
