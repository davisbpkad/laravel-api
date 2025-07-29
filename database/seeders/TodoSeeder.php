<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $user = User::where('email', 'user@example.com')->first();

        // Clear existing todos to avoid duplicates during re-seeding
        Todo::truncate();

        if ($admin) {
            // Admin todos
            Todo::create([
                'nama' => 'Review user reports',
                'deskripsi' => 'Check all user reports and respond to feedback',
                'user_id' => $admin->id,
                'due_date' => now()->addDays(3),
            ]);

            Todo::create([
                'nama' => 'Update system documentation',
                'deskripsi' => 'Update API documentation with new endpoints',
                'user_id' => $admin->id,
                'due_date' => now()->addWeek(),
            ]);

            Todo::create([
                'nama' => 'Database backup',
                'deskripsi' => 'Perform weekly database backup',
                'user_id' => $admin->id,
                'due_date' => now()->addDays(1),
                'completed_at' => now()->subHour(),
            ]);
        }

        if ($user) {
            // User todos
            Todo::create([
                'nama' => 'Complete project proposal',
                'deskripsi' => 'Finish writing the project proposal for the new client',
                'user_id' => $user->id,
                'due_date' => now()->addDays(5),
            ]);

            Todo::create([
                'nama' => 'Team meeting preparation',
                'deskripsi' => 'Prepare agenda and materials for tomorrow\'s team meeting',
                'user_id' => $user->id,
                'due_date' => now()->addDay(),
            ]);

            Todo::create([
                'nama' => 'Learn Laravel Sanctum',
                'deskripsi' => 'Study Laravel Sanctum documentation and implement authentication',
                'user_id' => $user->id,
                'due_date' => now()->addDays(7),
                'completed_at' => now()->subDays(2),
            ]);

            // Overdue todo
            Todo::create([
                'nama' => 'Submit monthly report',
                'deskripsi' => 'Submit the monthly progress report to supervisor',
                'user_id' => $user->id,
                'due_date' => now()->subDays(2),
            ]);
        }
    }
}
