<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::firstOrCreate(
            [
                'email' => 'admin@gmail.com',
                'phone' => '09111111111',
            ],
            [
                'name' => 'Admin Name',
                'profile_image' => '',
                'password' => Hash::make(123123123),
            ]
        );

        Staff::firstOrCreate(
            [
                'email' => 'staff@gmail.com',
                'phone' => '09111111111',
            ],
            [
                'name' => 'Staff Name',
                'profile_image' => '',
                'password' => Hash::make(123123123),
            ]
        );
    }
}
