<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        $newAdmin = User::create([
            'name' => 'Nita Bawel Admin',
            'email' => 'nitabaweladmin@admin.com',
            'password' => bcrypt('87654321'),
        ]);

        $newAdmin->assignRole($adminRole);
    }
}