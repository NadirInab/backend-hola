<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(['email' => 'admin@agadirquest.com'], [
            'name' => 'AgadirQuest Admin',
            'password' => Hash::make('Agadir2024!'),
            'phone' => '212600000000',
            'role' => User::ROLE_ADMIN,
        ]);

        // User::factory(10)->create([
        //     'password' => Hash::make('password'),
        //     'role' => User::ROLE_CUSTOMER,
        // ]);
    }
}
