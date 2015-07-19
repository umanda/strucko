<?php

use Illuminate\Database\Seeder;

class DefinitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Definition::class, 5)->create();
    }
}
