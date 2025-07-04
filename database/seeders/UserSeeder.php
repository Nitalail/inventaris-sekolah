<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $penggunaRole = Role::firstOrCreate(['name' => 'pengguna']);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Create student user
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@siswa.com'],
            [
                'name' => 'Siswa',
                'email' => 'siswa@siswa.com',
                'password' => bcrypt('12345678'),
                'role' => 'pengguna',
                'email_verified_at' => now(),
            ]
        );

        // Assign pengguna role
        if (!$siswa->hasRole('pengguna')) {
            $siswa->assignRole($penggunaRole);
        }

        // Only show info messages if running from command line
        if ($this->command) {
            $this->command->info('Users seeded successfully!');
            $this->command->info('Admin: admin@admin.com / 12345678');
            $this->command->info('Siswa: siswa@siswa.com / 12345678');
        }
    }
} 