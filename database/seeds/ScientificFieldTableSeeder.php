<?php

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
        factory(App\ScientificField::class, 5)->create();
    }
}
