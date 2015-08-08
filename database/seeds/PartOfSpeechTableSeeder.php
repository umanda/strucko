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
            ['part_of_speech' => 'noun'],
            ['part_of_speech' => 'verb'],
            ['part_of_speech' => 'pronoun'],
            ['part_of_speech' => 'adverb'],
            ['part_of_speech' => 'preposition'],
            ['part_of_speech' => 'conjunction'],
            ['part_of_speech' => 'interjection'],
        ];
        
        foreach ($partOfSpeeches as $partOfSpeech) {
            PartOfSpeech::create($partOfSpeech);
        }
    }
}
