<?php

use Illuminate\Database\Seeder;

class TermStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\TermStatus::class, 3)->create();
    }
}
