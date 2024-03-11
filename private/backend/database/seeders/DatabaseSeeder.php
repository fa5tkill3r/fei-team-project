<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $mainUser = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test.user@email.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);
        $users = User::factory(10)->create();

        DB::table('teams')->insert([
            'name' => 'Test Team',
            'description' => 'This is a test team',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_team')->insert([
            'team_id' => 1,
            'user_id' => 1,
            'role_id' => 1,
        ]);

        foreach ($users as $user) {
            DB::table('user_team')->insert([
                'team_id' => 1,
                'user_id' => $user->id,
                'role_id' => 2,
            ]);
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
