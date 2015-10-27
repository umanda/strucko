<?php

use League\Csv\Reader;
use Illuminate\Database\Seeder;
use App\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fake languages.
        // factory(App\Language::class, 5)->create();
        
        // ISO 639-3 Code Tables Set Download: http://www-01.sil.org/iso639-3/download.asp
        // $csv = Reader::createFromPath('database/seeds/data/iso-639-3_20150505.tab');
        
        // New CSV file with locale attribute.
        $csv = Reader::createFromPath('database/seeds/data/languages.csv');
        
        // Set delimiter to tab.
        // $csv->setDelimiter('	');
        
        // Set delimiter to ;
        $csv->setDelimiter(';');
        
        // $data is the iterator. Skip the first row, usually the header.
        // Original limit: 7865
        $data = $csv->setOffset(1)->setLimit(7865)->query();
        
        foreach ($data as $index => $row) {
            Language::create([
                'id' => $row[0],
                'part2b' => $row[1] ?: null,
                'part2t' => $row[2] ?: null,
                'part1' => $row[3] ?: null,
                'scope' => $row[4],
                'type' => $row[5],
                'ref_name' => $row[6],
                'comment' => $row[7] ?: null,
                'locale' => $row[11] ?: null,
            ]);
        }
        
        // Optionally: set languages that have 'part1' as active.
        Language::whereNotNull('locale')->update(['active' => 1]);
        
    }
}
