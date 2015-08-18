<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed with fake role.
        // factory(App\Role::class, 3)->create();
        
        // Seed with real life roles.
        $roles = [
            ['id'=> 250, 'role' => 'Banned'],
            ['id'=> 500, 'role' => 'Registered user'],
            ['id'=> 750, 'role' => 'Confirmed user'],
            ['id'=> 875, 'role' => 'Editor'],
            ['id'=> 1000, 'role' => 'Administrator'],
            ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
