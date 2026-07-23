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
        $this->call(CatalogProductSeeder::class);

        $email = env('SUPER_ADMIN_EMAIL');
        $password = env('SUPER_ADMIN_PASSWORD');

        if (! $email || ! $password) {
            if (! app()->environment(['local', 'testing'])) {
                $this->command?->warn('Super admin was not seeded: configure SUPER_ADMIN_EMAIL and SUPER_ADMIN_PASSWORD.');

                return;
            }

            $email = 'test@example.com';
            $password = 'password';
        }

        $superAdmin = User::updateOrCreate(
            ['email' => $email],
            [
                'username' => env('SUPER_ADMIN_NAME', 'CoffeeShop Owner'),
                'password' => $password,
            ]
        );

        $superAdmin->forceFill([
            'is_admin' => true,
            'role' => 'super_admin',
            'is_active' => true,
        ])->save();
    }
}
