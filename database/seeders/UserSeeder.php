<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ["name" => "User1", "email" => "user1@gmail.com", "password" => Hash::make('asdqwe123')],
            ["name" => "User2", "email" => "user2@gmail.com", "password" => Hash::make('asdqwe123')],
            ["name" => "User3", "email" => "user3@gmail.com", "password" => Hash::make('asdqwe123')],
            ["name" => "User4", "email" => "user4@gmail.com", "password" => Hash::make('asdqwe123')],
            ["name" => "User5", "email" => "user5@gmail.com", "password" => Hash::make('asdqwe123')]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
