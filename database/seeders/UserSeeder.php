<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default credentials
        \App\Models\User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin',
                'email_verified_at' => now(),
                'password' => bcrypt('jammschoolAdmin1594_'), // password
                'gender' => 'male',
                'stageRegister' => 8,
                'role_id' => 2,
                'active' => 1,
                'remember_token' => Str::random(10)
            ],
        ]);

        // Fake users
        User::factory()->times(9)->create();
    }
}
