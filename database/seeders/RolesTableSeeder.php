<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles =  ['admin', 'employee', 'guest'];
    

    foreach ($roles as $roleName) {
            \App\Models\Role::updateOrCreate(['rolename' => $roleName], ['rolename' => $roleName]);
    }
    }
}
