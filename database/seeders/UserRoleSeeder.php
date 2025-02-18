<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'Superadmin',
            'Description' => 'Super Admin'
        ]);

        Role::create([
            'role_name' => 'Admin',
            'Description' => 'Admin'
        ]);

        Role::create([
            'role_name' => 'Staff',
            'Description' => 'Staff'
        ]);
    }
}
