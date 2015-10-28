<?php

use Illuminate\Database\Seeder;
use App\Concept;
use App\Http\Controllers\Traits\ManagesTerms;
use App\ScientificField;
use App\PartOfSpeech;

class TermTableSeeder extends Seeder
{
    use ManagesTerms;
    // TODO remove from fillable: user_id in Definitions
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
        
        $this->seedFirstFile($firstFile, $path, $scientificField);
        
        // Ok, now use other files for other languages
        $files = [
            ['name' => 'en.xml', 'language_id' => 'eng'],
        ];

        // Seed the database with other files
        

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
            $termEntryId = (string)$termEntry['id'];
            $concept = Concept::firstOrCreate(['term_entry_id' => $termEntryId]);
            // Create definition
            $concept->definitions()->create([
                'user_id' => 1,
                'definition' => (string)$termEntry->langSet->descripGrp->descrip,
                'language_id' => $firstFile['language_id'],
                'source' => 'Entry from the Microsoft Language Portal. © 2015 Microsoft Corporation. All rights reserved.',
                'link' => 'http://www.microsoft.com/Language/en-US/Terminology.aspx'
            ]);
            
            // Get term
            $term = (string)$termEntry->langSet->ntig->termGrp->term;
            // Get part of speech
            $partOfSpeech = PartOfSpeech::firstOrCreate(['part_of_speech' => (string)$termEntry->langSet->ntig->termGrp->termNote]);
            // prepare slug
            $slug = str_slug(
                    $term . '-'
                    . $firstFile['language'] . '-'
                    . $partOfSpeech->part_of_speech . '-'
                    . $scientificField->scientific_field . '-'
                    . str_random()
                    );
            // Get menu letter
            $menuLetter = $this->prepareMenuLetter($term, $firstFile['language_id']);
            // Create term
            $concept->terms()->create([
                'term' => $term,
                'slug' => $slug,
                'menu_letter' => $menuLetter,
                'user_id' => 1,
                'language_id' => $firstFile['language_id'],
                'part_of_speech_id' => $partOfSpeech->id,
                'scientific_field_id' => $scientificField->id,
                'is_abbreviation' => false,
            ]);
            $reader->next('termEntry');
        }
        $reader->close();
    }
    
    protected function seedOtherFiles($files, $path)
    {
        foreach ($files as $file) {
            $reader = new XMLReader;
            $reader->open($path . $file['name']);

            // Move to the first termEntry
            while ($reader->read() && $reader->name != 'termEntry') {}
            
            // Iterate over each termEntry and store data in database
            while ($reader->name == 'termEntry') {
                // Use SimpleXML to work with current entry.
                $termEntry = new SimpleXMLElement($reader->readOuterXML());
                
                $termEntryId = $termEntry['id'];
                // Check if the concept exists
                if (Concept::where('term_entry_id', $termEntryId)->exists()) {
                    // Get existing concept
                    $concept = Concept::where('term_entry_id', $termEntryId)->first();
                    echo 'postoji, id je ' . $concept->id;
                }
                else {
                    $concept = Concept::create(['term_entry_id' => $termEntryId]);
                    // Add definition
                    // Add term (this is in english)
                    echo 'ne postoji, napravio sam ' . $concept->id;
                }
                // Access attributes of an element just as you would elements of an array.
                // Go to next termEntry
                $reader->next('termEntry');
            }

            // Close reader
            $reader->close();
        }
    }

}
