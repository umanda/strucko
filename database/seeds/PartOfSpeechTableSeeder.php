<?php

use App\PartOfSpeech;
use Illuminate\Database\Seeder;

class PartOfSpeechTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fake data.
        // factory(App\PartOfSpeech::class, 5)->create();
        
        // Real data.
        $partOfSpeeches = [
            ['part_of_speech' => 'Noun'],
            ['part_of_speech' => 'Verb'],
            ['part_of_speech' => 'Pronoun'],
            ['part_of_speech' => 'Adverb'],
            ['part_of_speech' => 'Preposition'],
            ['part_of_speech' => 'Conjunction'],
            ['part_of_speech' => 'Interjection'],
            ['part_of_speech' => 'Proper Noun'],
            ['part_of_speech' => 'Adjective'],
            ['part_of_speech' => 'Other'],
            
        ];
        
        foreach ($partOfSpeeches as $partOfSpeech) {
            PartOfSpeech::create($partOfSpeech);
        }
    }
}
