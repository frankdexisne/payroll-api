<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin',
                'password' => 'LSQwLwaN9278X+TmBgzS3w=='
            ],
            [
                'name' => 'Registrar',
                'email' => 'registrar',
                'password' => 'LSQwLwaN9278X+TmBgzS3w=='
            ],
            [
                'name' => 'HR',
                'email' => 'hr',
                'password' => 'LSQwLwaN9278X+TmBgzS3w=='
            ]
        ];
        User::upsert($users, ['email'], ['name','email','password']);
    }
}
