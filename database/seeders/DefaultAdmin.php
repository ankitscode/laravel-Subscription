<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultAdmin extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'developer',
                'full_name' => 'developer',
                'email' => 'devsoft7pm@gmail.com',
                'password' => Hash::make('!@admin123'),
                'is_active' => 1,
                'phone' => 9998553040,
                'birthdate' => 2020-11-30,
                'uuid' =>'c4dcce75-5b80-4ee7-af5e-6d7ce455ee5f',
            ],
        ];

        DB::table('users')->insertOrIgnore($data);
    }
}
