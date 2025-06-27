<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'pengguna']);

        // Create admin user
        $superAdmin = User::create([
            'name' => 'Administrator Sekolah',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);

        $superAdmin->assignRole($adminRole);

        // Create regular user example
        $regularUser = User::create([
            'name' => 'Pengguna Biasa',
            'email' => 'user@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $regularUser->assignRole($userRole);
    }
}