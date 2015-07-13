<?php

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
        factory(App\PartOfSpeech::class, 5)->create();
    }
}
