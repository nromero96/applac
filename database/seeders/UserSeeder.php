<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        User::create([
            'name' => 'Nilton Romero',
            'email' => 'niltondeveloper96@gmail.com',
            'password' => bcrypt('RegM7xSHhBuVG6'),
            'status' => 'active',
            'photo' => 'profile-19.jpeg'
        ])->assignRole('Administrator');

        User::create([
            'name' => 'Homero Herrera',
            'email' => 'homero.herrera@lacship.com',
            'password' => bcrypt('123456789'),
            'status' => 'active',
            'photo' => 'profile-26.jpeg'
        ])->assignRole('Administrator');

        User::create([
            'name' => 'Nicholas Herrera',
            'email' => 'nicholas.herrera@lacship.com',
            'password' => bcrypt('123456789'),
            'status' => 'active',
            'photo' => 'profile-3.jpeg'
        ])->assignRole('Administrator');

        User::create([
            'name' => 'Jhon Perez',
            'email' => 'hl@example.com',
            'password' => bcrypt('123456789'),
            'status' => 'inactive',
            'photo' => 'profile-13.jpeg'
        ])->assignRole('Customer');

    }
}
