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
            ['id'=> 250, 'role' => 'Banned', 'vote_weight' => 0],
            ['id'=> 500, 'role' => 'Registered user', 'vote_weight' => 1],
            ['id'=> 750, 'role' => 'Confirmed user', 'vote_weight' => 2],
            ['id'=> 875, 'role' => 'Editor', 'vote_weight' => 3],
            ['id'=> 1000, 'role' => 'Administrator', 'vote_weight' => 4],
            ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
