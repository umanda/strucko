<?php

use Illuminate\Database\Seeder;

class ScientificBranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ScientificBranch::class, 5)->create();
    }
}
