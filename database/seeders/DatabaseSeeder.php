<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $superAdmin = User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'username' => 'Test User',
            'password' => 'password',
        ]);

        $superAdmin->forceFill([
            'is_admin' => true,
            'role' => 'super_admin',
            'is_active' => true,
        ])->save();
    }
}
