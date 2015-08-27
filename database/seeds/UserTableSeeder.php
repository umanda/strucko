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
        factory(App\User::class, 5)->create();
        
        // TODO Delete this before production
        // Also create demo user.
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('demo')
        ]);
        
         // Also create admin user.
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'role_id' => 1000
        ]);
    }
}
