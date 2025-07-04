<?php

namespace App\Console\Commands;

use Database\Seeders\UserSeeder;
use Illuminate\Console\Command;

class SeedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with default admin and student users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding users...');
        
        $seeder = new UserSeeder();
        $seeder->run();
        
        $this->info('Users seeded successfully!');
        $this->info('Admin: admin@admin.com / 12345678');
        $this->info('Siswa: siswa@siswa.com / 12345678');
        
        return Command::SUCCESS;
    }
}
