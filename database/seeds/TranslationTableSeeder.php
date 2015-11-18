<?php

use Illuminate\Database\Seeder;
use App\Term;
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
        $startItem = 0;
        $endItem = 5000;
        
        // Get terms.
        $terms = Term::skip($startItem)->take($endItem)->get();
        // For each term, get terms with the same concept_id and scientific_field_id, 
        // but different language_id
        foreach($terms as $term) {
            $translations = Term::where('concept_id', $term->concept_id)
                    ->where('scientific_field_id', $term->scientific_field_id)
                    ->where('language_id', '<>', $term->language_id)
                    ->get();
            // Foreach translation create entry in the translations table.
            foreach ($translations as $translation) {
                Translation::firstOrCreate([
                    'term_id' => $term->id,
                    'translation_id' => $translation->id,
                    'user_id' => $translation->user_id
                ]);
                        
            }
        }
    }
}
