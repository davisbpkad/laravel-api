<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FreshSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fresh-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables, run migrations and seed the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dropping all tables and running fresh migration...');
        
        // Fresh migration
        $this->call('migrate:fresh');
        
        $this->info('Seeding database...');
        
        // Seed the database
        $this->call('db:seed');
        
        $this->info('Database has been freshly seeded!');
        
        $this->line('');
        $this->line('Default accounts created:');
        $this->line('Admin: admin@example.com / password');
        $this->line('User:  user@example.com / password');
        
        return 0;
    }
}
