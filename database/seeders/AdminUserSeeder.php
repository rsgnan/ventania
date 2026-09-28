<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'email' => 'rsgnan@proton.me',
            'password' => 'admin',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
