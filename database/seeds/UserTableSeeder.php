<?php

use Illuminate\Database\Seeder;
use App\User;

// use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create fake users.
        factory(App\User::class, 50)->create();
        
        // Also create demo user.
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('demo')
        ]);
    }
}
