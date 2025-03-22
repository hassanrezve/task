<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use App\Models\Category;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => Role::ADMIN->value,
        ]);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => Role::MANAGER->value,
        ]);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => Role::USER->value,
        ]);

        $categories = ['Work', 'Personal', 'Urgent'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        Task::create([
            'title' => 'Task for Manager',
            'description' => 'This task is assigned to a Manager by Admin',
            'user_id' => $manager->id,
            'created_by' => $admin->id,
            'category_id' => 1,
            'priority' => 'high',
            'due_date' => now()->addDays(3),
            'status' => 'pending',
        ]);

        Task::create([
            'title' => 'Task for User',
            'description' => 'This task is assigned to a User by Manager',
            'user_id' => $user->id,
            'created_by' => $manager->id,
            'category_id' => 2,
            'priority' => 'medium',
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);
    }
}
