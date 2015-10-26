<?php

use Illuminate\Database\Seeder;
use Sabre\Xml;

class TermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   // Dummy data
        // factory(App\Term::class, 50)->create();
        
        // Real data from XML sources
        $reader = new Xml\Reader();
        $reader->open('database/seeds/data/term_collections/english_demo.xml');
        $output = $reader->parse();
        
        var_dump($output);
    }
}
