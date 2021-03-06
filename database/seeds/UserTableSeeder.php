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
        // Dummy users.
        // factory(App\User::class, 5)->create();
        
        // Create admin user - needed for seeding.
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt(str_random()),
            'verified' => true,
            'role_id' => 1000
        ]);
        
        /** Create demo user - for testing.
        User::create([
            'name' => 'Demo',
            'email' => 'demo@example.com',
            'password' => bcrypt(str_random()),
            'verified' => true,
            'role_id' => 500
        ]);
        */
        
        
        
        
    }
}
