<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'admin',
            'surname' => 'admin',
            'email' => 'sales@leica.am',
            'role_id' =>  1,
            'username' => 'leica.admin',
            'password' => Hash::make('admin.leica.2024')
        ]);

    }
}
