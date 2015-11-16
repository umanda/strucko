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
        
        $this->seedFirstFile($firstFile, $path, $scientificField);
        
        // Ok, now use other files for other languages
        $files = [
            ['name' => 'af.xml', 'language_id' => 'afr', 'language' => 'Afrikaans'],
            ['name' => 'sq.xml', 'language_id' => 'sqi', 'language' => 'Albanian'],
            ['name' => 'az.xml', 'language_id' => 'aze', 'language' => 'Azerbaijani'],
            ['name' => 'eu.xml', 'language_id' => 'eus', 'language' => 'Basque'],
            ['name' => 'be.xml', 'language_id' => 'bel', 'language' => 'Belarusian'],
            ['name' => 'bs.xml', 'language_id' => 'bos', 'language' => 'Bosnian'],
            ['name' => 'bg.xml', 'language_id' => 'bul', 'language' => 'Bulgarian'],
            ['name' => 'ca.xml', 'language_id' => 'cat', 'language' => 'Catalan'],
            ['name' => 'hr.xml', 'language_id' => 'hrv', 'language' => 'Croatian'],
            ['name' => 'cs.xml', 'language_id' => 'ces', 'language' => 'Czech'],
            ['name' => 'da.xml', 'language_id' => 'dan', 'language' => 'Danish'],
            ['name' => 'nl.xml', 'language_id' => 'nld', 'language' => 'Dutch'],
            ['name' => 'et.xml', 'language_id' => 'est', 'language' => 'Estonian'],
            ['name' => 'fil.xml', 'language_id' => 'fil', 'language' => 'Filipino'],
            ['name' => 'fi.xml', 'language_id' => 'fin', 'language' => 'Finnish'],
            ['name' => 'fr.xml', 'language_id' => 'fra', 'language' => 'French'],            
            ['name' => 'gl.xml', 'language_id' => 'glg', 'language' => 'Galician'],
            ['name' => 'de.xml', 'language_id' => 'deu', 'language' => 'German'],
            ['name' => 'el.xml', 'language_id' => 'ell', 'language' => 'Greek'],
            ['name' => 'ha.xml', 'language_id' => 'hau', 'language' => 'Hausa'],
            ['name' => 'hu.xml', 'language_id' => 'hun', 'language' => 'Hungarian'],
            ['name' => 'is.xml', 'language_id' => 'isl', 'language' => 'Icelandic'],
            ['name' => 'ig.xml', 'language_id' => 'ibo', 'language' => 'Igbo'],
            ['name' => 'id.xml', 'language_id' => 'ind', 'language' => 'Indonesian'],
            ['name' => 'iu.xml', 'language_id' => 'iku', 'language' => 'Inuktitut'],
            ['name' => 'ga.xml', 'language_id' => 'gle', 'language' => 'Irish'],
            ['name' => 'xh.xml', 'language_id' => 'xho', 'language' => 'Xhosa'],
            ['name' => 'zu.xml', 'language_id' => 'zul', 'language' => 'Zulu'],
            ['name' => 'it.xml', 'language_id' => 'ita', 'language' => 'Italian'],
            ['name' => 'kk.xml', 'language_id' => 'kaz', 'language' => 'Kazakh'],
            ['name' => 'rw.xml', 'language_id' => 'kin', 'language' => 'Kinyarwanda'],
            ['name' => 'ky.xml', 'language_id' => 'kir', 'language' => 'Kirghiz'],
            ['name' => 'lo.xml', 'language_id' => 'lao', 'language' => 'Lao'],
            ['name' => 'lv.xml', 'language_id' => 'lav', 'language' => 'Latvian'],
            ['name' => 'lt.xml', 'language_id' => 'lit', 'language' => 'Lithuanian'],
            ['name' => 'mk.xml', 'language_id' => 'mkd', 'language' => 'Macedonian'],
            ['name' => 'ms.xml', 'language_id' => 'msa', 'language' => 'Malay (macrolanguage)'],
            ['name' => 'ml.xml', 'language_id' => 'mal', 'language' => 'Malayalam'],
            ['name' => 'mt.xml', 'language_id' => 'mlt', 'language' => 'Maltese'],
            ['name' => 'mi.xml', 'language_id' => 'mri', 'language' => 'Maori'],
            ['name' => 'mr.xml', 'language_id' => 'mar', 'language' => 'Marathi'],
            ['name' => 'ne.xml', 'language_id' => 'nep', 'language' => 'Nepali (macrolanguage)'],
            ['name' => 'nb.xml', 'language_id' => 'nob', 'language' => 'Norwegian Bokmål'],
            ['name' => 'nn.xml', 'language_id' => 'nno', 'language' => 'Norwegian Nynorsk'],
            ['name' => 'or.xml', 'language_id' => 'ori', 'language' => 'Oriya (macrolanguage)'],
            ['name' => 'ps.xml', 'language_id' => 'pus', 'language' => 'Pushto'],
            ['name' => 'fa.xml', 'language_id' => 'fas', 'language' => 'Persian'],
            ['name' => 'pl.xml', 'language_id' => 'pol', 'language' => 'Polish'],
            ['name' => 'pt.xml', 'language_id' => 'por', 'language' => 'Portuguese'],
            ['name' => 'pa.xml', 'language_id' => 'pan', 'language' => 'Panjabi'],
            ['name' => 'ro.xml', 'language_id' => 'ron', 'language' => 'Romanian'],
            ['name' => 'ru.xml', 'language_id' => 'rus', 'language' => 'Russian'],
            ['name' => 'gd.xml', 'language_id' => 'gla', 'language' => 'Scottish Gaelic'],
            ['name' => 'sr.xml', 'language_id' => 'srp', 'language' => 'Serbian'],
            ['name' => 'nso.xml', 'language_id' => 'nso', 'language' => 'Pedi'],
            ['name' => 'tn.xml', 'language_id' => 'tsn', 'language' => 'Tswana'],
            ['name' => 'sd.xml', 'language_id' => 'snd', 'language' => 'Sindhi'],
            ['name' => 'si.xml', 'language_id' => 'sin', 'language' => 'Sinhala'],
            ['name' => 'sk.xml', 'language_id' => 'slk', 'language' => 'Slovak'],
            ['name' => 'sl.xml', 'language_id' => 'slv', 'language' => 'Slovenian'],
            ['name' => 'es.xml', 'language_id' => 'spa', 'language' => 'Spanish'],
            ['name' => 'sv.xml', 'language_id' => 'swe', 'language' => 'Swedish'],
            ['name' => 'tg.xml', 'language_id' => 'tgk', 'language' => 'Tajik'],
            ['name' => 'ta.xml', 'language_id' => 'tam', 'language' => 'Tamil'],
            ['name' => 'tt.xml', 'language_id' => 'tat', 'language' => 'Tatar'],
            ['name' => 'te.xml', 'language_id' => 'tel', 'language' => 'Telugu'],
            ['name' => 'th.xml', 'language_id' => 'tha', 'language' => 'Thai'],
            ['name' => 'ti.xml', 'language_id' => 'tir', 'language' => 'Tigrinya'],
            ['name' => 'tr.xml', 'language_id' => 'tur', 'language' => 'Turkish'],
            ['name' => 'tk.xml', 'language_id' => 'tuk', 'language' => 'Turkmen'],
            ['name' => 'uk.xml', 'language_id' => 'ukr', 'language' => 'Ukrainian'],
            ['name' => 'ur.xml', 'language_id' => 'urd', 'language' => 'Urdu'],
            ['name' => 'ug.xml', 'language_id' => 'uig', 'language' => 'Uighur'],
            ['name' => 'uz.xml', 'language_id' => 'uzb', 'language' => 'Uzbek'],
            ['name' => 'vi.xml', 'language_id' => 'vie', 'language' => 'Vietnamese'],
            ['name' => 'cy.xml', 'language_id' => 'cym', 'language' => 'Welsh'],
            ['name' => 'wo.xml', 'language_id' => 'wol', 'language' => 'Wolof'],
            ['name' => 'yo.xml', 'language_id' => 'yor', 'language' => 'Yoruba'],
            
        ];

        // Seed the database with other files
        $this->seedOtherFiles($files, $firstFile, $path, $scientificField);

    }
    /** 
     * Seed the english file first.
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
            $seedDefinition = trim((string)$termEntry->langSet->descripGrp->descrip);
            $seedTerm = trim((string)$termEntry->langSet->ntig->termGrp->term);
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
                        
            while ($reader->name == 'termEntry') {
                
                // Use SimpleXML to work with current entry.
                $termEntry = new SimpleXMLElement($reader->readOuterXML());

                $seedTermEntryId = (string)$termEntry['id'];
                $seedDefinition = trim((string)$termEntry->langSet->descripGrp->descrip);
                $seedTerm = trim((string)$termEntry->langSet->ntig->termGrp->term);
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
                    $translationSeedTerm = trim((string)$termEntry->langSet[1]->ntig->termGrp->term);
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
                    $translationSeedTerm = trim((string)$termEntry->langSet[1]->ntig->termGrp->term);
                    $translationTerm = $this->tryToGetTerm($translationSeedTerm, $file['language_id'], $seedPartOfSpeech->id, $scientificField->id);
                    
                    if(is_null($translationTerm)) {
                        
                        $translationSlug = $this->prepareSlugForSeededTerms(
                            $translationSeedTerm,
                            $file['language'],
                            $seedPartOfSpeech->part_of_speech,
                            $scientificField->scientific_field
                        );
                        $translationMenuLetter = $this->prepareMenuLetter($translationSeedTerm, $file['language_id']);
                        
                        $originalTerm->concept->terms()->create([
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

    protected function prepareSlugForSeededTerms($term, $language, $partOfSpeech, $scientificField)
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
