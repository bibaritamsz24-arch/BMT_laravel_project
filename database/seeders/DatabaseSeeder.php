<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->upsertUser('admin@example.com', 'Admin', 'admin');
        $this->upsertUser('teacher@example.com', 'Teacher', 'teacher');
    }

    private function upsertUser(string $email, string $name, string $role): void
    {
        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'role' => $role,
            ],
        );
    }
}
