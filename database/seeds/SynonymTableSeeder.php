<?php

use Illuminate\Database\Seeder;

class SynonymTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Synonym::class, 50)->create();
    }
}
