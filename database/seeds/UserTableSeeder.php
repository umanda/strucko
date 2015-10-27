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
        // TODO Delete this before production
        // Create admin user.
        User::create([
            'name' => 'Strucko Admin',
            'email' => 'admin@strucko.com',
            'password' => bcrypt('admin'),
            'role_id' => 1000
        ]);
        // Also create demo user.
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('demo')
        ]);
        // Create Microsoft Terminology seeding user
        User::create([
            'name' => 'MLT Seeder',
            'email' => 'noreply@example.com',
            'password' => bcrypt(str_random())
        ]);
        // Create fake users.
        factory(App\User::class, 5)->create();
        
        
    }
}
