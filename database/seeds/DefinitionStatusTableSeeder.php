<?php

use Illuminate\Database\Seeder;

class DefinitionStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\DefinitionStatus::class, 5)->create();
    }
}
