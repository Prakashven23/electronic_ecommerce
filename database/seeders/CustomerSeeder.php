<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '9876543210',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '9876543211',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'phone' => '9876543212',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
