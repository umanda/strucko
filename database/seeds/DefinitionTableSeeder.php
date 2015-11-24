<?php

use Illuminate\Database\Seeder;
use App\Definition;

class DefinitionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Dummy data
        //factory(App\Definition::class, 5)->create();

        $counter = 0;
        $startTime = time();
        // Get definitions with their terms
        Definition::whereNull('term_id')
                ->chunk(200, function ($definitions) use (&$counter, $startTime) {

                    // For each concpets, get each term.
                    foreach ($definitions as $definition) {
                        // Load terms
                        $languageId = $definition->language_id;
                        $definition->load(['concept.terms' => function ($query) use ($languageId) {
                                $query->where('language_id', $languageId);
                            }]);

                        foreach ($definition->concept->terms as $term) {

                            $replica = $definition->replicate();
                            $replica->term_id = $term->id;
                            $replica->save();
                        }

                        if (( ++$counter) % 100 == 0) {
                            echo $counter . ' ' . round(abs(time() - $startTime) / 60, 2) . " minutes\n";
                        }
                    }
                });
    }

}
