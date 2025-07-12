<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@techstore.com',
            'password' => Hash::make('admin123'),
        ]);
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@techstore.com');
        $this->command->info('Password: admin123');
    }
}
