<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::insert([
            [
                'role' => '1',
                'name' => 'user',
            ],
            [
                'role' => '2',
                'name' => 'admin',
            ],
            [
                'role' => '3',
                'name' => 'super',
            ],
        ]);
        // Default credentials
    }
}
