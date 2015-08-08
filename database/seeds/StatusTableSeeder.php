<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed with fake statuses.
        // factory(App\Status::class, 3)->create();
        
        // Seed with real life statuses.
        $statuses = [
            ['id'=> 250, 'status' => 'Rejected'],
            ['id'=> 500, 'status' => 'Suggested'],
            ['id'=> 750, 'status' => 'Edited'],
            ['id'=> 1000, 'status' => 'Approved'],
            ];
        
        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
