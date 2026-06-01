<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    public function run(): void
    {
        $administrators = [
            [
                'username' => 'keuchik',
                'password' => Hash::make('password123'),
                'role' => 'keuchik',
            ],
            [
                'username' => 'sekdes',
                'password' => Hash::make('password123'),
                'role' => 'sekdes',
            ],
            [
                'username' => 'operator',
                'password' => Hash::make('password123'),
                'role' => 'operator',
            ],
        ];

        foreach ($administrators as $admin) {
            Administrator::create($admin);
        }
    }
}
