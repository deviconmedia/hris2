<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Iconmedia',
            'email' => 'dev.iconmedia@gmail.com',
            'password' => bcrypt('password'),
            'phone' => '081234567892',
            'role_id' => 1,
        ]);
    }
}
