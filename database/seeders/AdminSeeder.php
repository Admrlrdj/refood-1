<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name'     => 'Super Admin',
            'username' => 'admin',
            'email'    => 'admin@refood.com',
            'password' => Hash::make('admin123'),
        ]);
    }
}
