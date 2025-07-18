<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'phone' => '081234567890',
            'email' => 'admin@example.com',
            'password' => Hash::make('pastibisa'),
        ]);
    }
}