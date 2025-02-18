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
            'name' => 'Efron Paduansi',
            'email' => 'eufrondpaduansi@iconmedia.co.id',
            'password' => bcrypt('password'),
            'phone' => '081359856450',
            'role_id' => 1,
        ]);
    }
}
