<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert(
            [
                [
                    'name' => 'Amir hossein',
                    'email' => 'amir@gmail.com',
                    'password' => Hash::make('123456'),
                    'type' => 'admin'
                ],
                [
                    'name' => 'Member Test',
                    'email' => 'test@gmail.com',
                    'password' => Hash::make('123456'),
                    'type' => 'member'
                ]
            ]
        );
    }
}
