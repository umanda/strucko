<?php

use Illuminate\Database\Seeder;
use Sabre\Xml;

class TermTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Dummy data
        // factory(App\Term::class, 50)->create();
        // Real data from XML sources
        // http://stackoverflow.com/questions/1835177/how-to-use-xmlreader-in-php
        
        $reader = new XMLReader;
        $reader->open('database/seeds/data/term_collections/english_demo.xml');
        
        // Move to the first term entry
        while ($reader->read() && $reader->name != 'termEntry') {}
        
        while ($reader->name == 'termEntry') {
            // Use SimpleXML to work with current entry.
            $termEntry = new SimpleXMLElement($reader->readOuterXML());
            
            echo $termEntry->langSet->descripGrp->descrip;
            // Access attributes of an element just as you would elements of an array.
            
            // Go to next termEntry
            $reader->next('termEntry');
        }
        
        // Close reader
        $reader->close();
        
//        $z = new XMLReader;
//        $z->open('data.xml');
//
//        $doc = new DOMDocument;
//
//        // move to the first <product /> node
//        while ($z->read() && $z->name !== 'product');
//
//        // now that we're at the right depth, hop to the next <product/> until the end of the tree
//        while ($z->name === 'product') {
//            // either one should work
//            //$node = new SimpleXMLElement($z->readOuterXML());
//            $node = simplexml_import_dom($doc->importNode($z->expand(), true));
//
//            // now you can use $node without going insane about parsing
//            var_dump($node->element_1);
//
//            // go to next <product />
//            $z->next('product');
//        }
//        
//        $xml_reader = new XMLReader;
//        $xml_reader->open($feed_url);
//
//        // move the pointer to the first product
//        while ($xml_reader->read() && $xml_reader->name != 'product');
//
//        // loop through the products
//        while ($xml_reader->name == 'product') {
//            // load the current xml element into simplexml and we’re off and running!
//            $xml = simplexml_load_string($xml_reader->readOuterXML());
//
//            // now you can use your simpleXML object ($xml).
//            echo $xml->element_1;
//
//            // move the pointer to the next product
//            $xml_reader->next('product');
//        }
//
//        // don’t forget to close the file
//        $xml_reader->close();
    }

}
