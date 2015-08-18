<?php

use League\Csv\Reader;
Use App\ScientificField;
use Illuminate\Database\Seeder;

class ScientificFieldTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fake data
        // factory(App\ScientificField::class, 5)->create();
        
        // Sources for Croatian Regulation on scientific and artistic areas, fields and branches:
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2013_03_32_574.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2012_07_82_1917.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2009_09_118_2929.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2008_07_78_2563.html
        // http://narodne-novine.nn.hr/clanci/sluzbeni/2005_06_76_1500.html
        
        $csv = Reader::createFromPath('database/seeds/data/scientific-fields.csv');
        // Set delimiter
        $csv->setDelimiter(';');
        // $data is the iterator. S the limit because the last row is empty.
        $data = $csv->setOffset(1)->setLimit(69)->query();
        
        foreach ($data as $index => $row) {
            
            ScientificField::create([
                'id' => $row[0],
                'scientific_field' => $row[1],
                'mark' => $row[2],
                'description' => $row[3] ?: null,
                'scientific_area_id' => $row[7],
            ]);
        }
    }
}
