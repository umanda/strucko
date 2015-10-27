<?php

use Illuminate\Database\Seeder;
use App\Concept;
use App\Http\Controllers\Traits\ManagesTerms;
use App\ScientificField;
use App\PartOfSpeech;
use App\Definition;
use App\Term;

class TermTableSeeder extends Seeder
{
    use ManagesTerms;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dummy data
        // factory(App\Term::class, 50)->create();
        // 
        // http://stackoverflow.com/questions/1835177/how-to-use-xmlreader-in-php
        // http://www.codeproject.com/Articles/582953/Working-with-XML-in-PHP
        // http://www.ibm.com/developerworks/library/x-xmlphp2/
        // http://www.ibm.com/developerworks/library/x-pullparsingphp/
        // 
        // Real data from XML sources
        // Source of XML files: http://www.microsoft.com/Language/en-US/Terminology.aspx
        // 
        // Path where we have stored XML files
        $path = 'database/seeds/data/term_collections/';
        $scientificField = ScientificField::where('scientific_field', 'Computing')->first();
        
        $firstFile = ['name' => 'en.xml', 'language_id' => 'eng', 'language' => 'English'];
        
        // $this->seedFirstFile($firstFile, $path, $scientificField);
        
        // Ok, now use other files for other languages
        $files = [
            ['name' => 'hr.xml', 'language_id' => 'hrv', 'language' => 'Croatian'],
            ['name' => 'af.xml', 'language_id' => 'afr', 'language' => 'Afrikaans'],
            ['name' => 'sq.xml', 'language_id' => 'sqi', 'language' => 'Albanian'],
            
        ];

        // Seed the database with other files
        $this->seedOtherFiles($files, $firstFile, $path, $scientificField);

    }
    /** I have to rewrite this, I can have miltiple terms with different
     * deffinitions.
     * 
     * @param type $firstFile
     * @param type $path
     * @param type $scientificField
     */
    protected function seedFirstFile($firstFile, $path, $scientificField)
    {
        $reader = new XMLReader;
        $reader->open($path . $firstFile['name']);
        // Move to the first termEntry
        while ($reader->read() && $reader->name != 'termEntry') {}
        // Iterate over each termEntry and store data in database
        while ($reader->name == 'termEntry') {
            // Use SimpleXML to work with current entry.
            $termEntry = new SimpleXMLElement($reader->readOuterXML());
            
            $seedTermEntryId = (string)$termEntry['id'];
            $seedDefinition = (string)$termEntry->langSet->descripGrp->descrip;
            $seedTerm = (string)$termEntry->langSet->ntig->termGrp->term;
            $seedPartOfSpeech = PartOfSpeech::firstOrCreate([
                    'part_of_speech' => (string)$termEntry->langSet->ntig->termGrp->termNote
                ]);
            $menuLetter = $this->prepareMenuLetter($seedTerm, $firstFile['language_id']);
            $userId = 1;
            
            // First check if the term already exists in the database.
            $term = $this->tryToGetTerm($seedTerm, $firstFile['language_id'], $seedPartOfSpeech->id, $scientificField->id);
            
            if(is_null($term)){
                // Create term and definition.
                $concept = Concept::create();
                // Prepare slug
                $slug = $this->prepareSlugForSeededTerms(
                        $seedTerm,
                        $firstFile['language'],
                        $seedPartOfSpeech->part_of_speech,
                        $scientificField->scientific_field
                    );
                // Create term
                $concept->terms()->create([
                    'term' => $seedTerm,
                    'slug' => $slug,
                    'menu_letter' => $menuLetter,
                    'user_id' => $userId,
                    'language_id' => $firstFile['language_id'],
                    'part_of_speech_id' => $seedPartOfSpeech->id,
                    'scientific_field_id' => $scientificField->id,
                    'is_abbreviation' => false,
                ]);
                // Create definition
                $definition = new Definition;
                $definition->user_id = $userId;
                $definition->concept_id = $concept->id;
                $definition->definition = $seedDefinition;
                $definition->term_entry_id = $seedTermEntryId;
                $definition->language_id = $firstFile['language_id'];
                $definition->source ='Entry from the Microsoft Language Portal. © 2015 Microsoft Corporation. All rights reserved.';
                $definition->link = 'http://www.microsoft.com/Language/en-US/Terminology.aspx';
                $definition->save();
            }
            else {
                // Term exist
                // If definition does not exist, create it. Use concept_id from term
                if ( ! Definition::where('term_entry_id', $seedTermEntryId)->exists()) {
                    $definition = new Definition;
                    $definition->user_id = $userId;
                    $definition->concept_id = $term->concept_id;
                    $definition->definition = $seedDefinition;
                    $definition->term_entry_id = $seedTermEntryId;
                    $definition->language_id = $firstFile['language_id'];
                    $definition->source ='Entry from the Microsoft Language Portal. © 2015 Microsoft Corporation. All rights reserved.';
                    $definition->link = 'http://www.microsoft.com/Language/en-US/Terminology.aspx';
                    $definition->save();
                }
            }
            
            $reader->next('termEntry');
        }
        $reader->close();
    }
    
    protected function seedOtherFiles($files, $firstFile, $path, $scientificField)
    {
        foreach ($files as $file) {
            $reader = new XMLReader;
            $reader->open($path . $file['name']);
            // Move to the first termEntry
            while ($reader->read() && $reader->name != 'termEntry') {}
            // Iterate over each termEntry and store data in database
            $count = 0;
            
            while ($reader->name == 'termEntry') {
                
                // Use SimpleXML to work with current entry.
                $termEntry = new SimpleXMLElement($reader->readOuterXML());

                $seedTermEntryId = (string)$termEntry['id'];
                $seedDefinition = (string)$termEntry->langSet->descripGrp->descrip;
                $seedTerm = (string)$termEntry->langSet->ntig->termGrp->term;
                $seedPartOfSpeech = PartOfSpeech::firstOrCreate([
                        'part_of_speech' => (string)$termEntry->langSet->ntig->termGrp->termNote
                    ]);
                $menuLetter = $this->prepareMenuLetter($seedTerm, $firstFile['language_id']);
                $userId = 1;
                
                // First check if the term already exists in the database.
                $originalTerm = $this->tryToGetTerm($seedTerm, $firstFile['language_id'], $seedPartOfSpeech->id, $scientificField->id);
                
                if(is_null($originalTerm)){
                    
                    // Create term and definition
                    $concept = Concept::create();
                    // Prepare slug
                    $slug = $this->prepareSlugForSeededTerms(
                            $seedTerm,
                            $firstFile['language'],
                            $seedPartOfSpeech->part_of_speech,
                            $scientificField->scientific_field
                        );
                    // Create original term
                    $concept->terms()->create([
                        'term' => $seedTerm,
                        'slug' => $slug,
                        'menu_letter' => $menuLetter,
                        'user_id' => $userId,
                        'language_id' => $firstFile['language_id'],
                        'part_of_speech_id' => $seedPartOfSpeech->id,
                        'scientific_field_id' => $scientificField->id,
                        'is_abbreviation' => false,
                    ]);
                    // Create definition
                    $definition = new Definition;
                    $definition->user_id = $userId;
                    $definition->concept_id = $concept->id;
                    $definition->definition = $seedDefinition;
                    $definition->term_entry_id = $seedTermEntryId;
                    $definition->language_id = $firstFile['language_id'];
                    $definition->source ='Entry from the Microsoft Language Portal. © 2015 Microsoft Corporation. All rights reserved.';
                    $definition->link = 'http://www.microsoft.com/Language/en-US/Terminology.aspx';
                    $definition->save();
                    // Create translation
                    $translationSeedTerm = (string)$termEntry->langSet[1]->ntig->termGrp->term;
                    $translationTerm = $this->tryToGetTerm($translationSeedTerm, $file['language_id'], $seedPartOfSpeech->id, $scientificField->id);
                    if(is_null($translationTerm)) {
                        
                        $translationSlug = $this->prepareSlugForSeededTerms(
                            $translationSeedTerm,
                            $file['language'],
                            $seedPartOfSpeech->part_of_speech,
                            $scientificField->scientific_field
                        );
                        $translationMenuLetter = $this->prepareMenuLetter($translationSeedTerm, $file['language_id']);
                        $concept->terms()->create([
                            'term' => $translationSeedTerm,
                            'slug' => $translationSlug,
                            'menu_letter' => $translationMenuLetter,
                            'user_id' => $userId,
                            'language_id' => $file['language_id'],
                            'part_of_speech_id' => $seedPartOfSpeech->id,
                            'scientific_field_id' => $scientificField->id,
                            'is_abbreviation' => false,
                        ]);
                    }
                }
                else {
                    
                    // Term exist
                    // If definition does not exist, create it. Use concept_id from term
                    if ( ! Definition::where('term_entry_id', $seedTermEntryId)->exists()) {
                        $definition = new Definition;
                        $definition->user_id = $userId;
                        $definition->concept_id = $originalTerm->concept_id;
                        $definition->definition = $seedDefinition;
                        $definition->term_entry_id = $seedTermEntryId;
                        $definition->language_id = $firstFile['language_id'];
                        $definition->source ='Entry from the Microsoft Language Portal. © 2015 Microsoft Corporation. All rights reserved.';
                        $definition->link = 'http://www.microsoft.com/Language/en-US/Terminology.aspx';
                        $definition->save();
                    }
                    // Create translation term if it does not exist. Use concept_id from original term
                    $translationSeedTerm = (string)$termEntry->langSet[1]->ntig->termGrp->term;
                    
                    $translationTerm = $this->tryToGetTerm($translationSeedTerm, $file['language_id'], $seedPartOfSpeech->id, $scientificField->id);
                    
                    if(is_null($translationTerm)) {
                       
                        $translationSlug = $this->prepareSlugForSeededTerms(
                            $translationSeedTerm,
                            $file['language'],
                            $seedPartOfSpeech->part_of_speech,
                            $scientificField->scientific_field
                        );
                        $translationMenuLetter = $this->prepareMenuLetter($translationSeedTerm, $file['language_id']);
                        $originalTerm->concept->create([
                            'term' => $translationSeedTerm,
                            'slug' => $translationSlug,
                            'menu_letter' => $translationMenuLetter,
                            'user_id' => $userId,
                            'language_id' => $file['language_id'],
                            'part_of_speech_id' => $seedPartOfSpeech->id,
                            'scientific_field_id' => $scientificField->id,
                            'is_abbreviation' => false,
                        ]);
                    }
                }

                $reader->next('termEntry');
            }
            $reader->close();
        }
    }

    protected function tryToGetTerm($term, $languageId, $partOfSpeechId, $scientificFieldId)
    {
        return Term::where('term', $term)
                ->where('language_id', $languageId)
                ->where('part_of_speech_id', $partOfSpeechId)
                ->where('scientific_field_id', $scientificFieldId)
                ->with('concept')
                ->first();
    }

    public function prepareSlugForSeededTerms($term, $language, $partOfSpeech, $scientificField)
    {
        return str_slug(
                    $term . '-'
                    . $language . '-'
                    . $partOfSpeech . '-'
                    . $scientificField . '-'
                    . str_random()
                );
    }

}
