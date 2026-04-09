<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Nestlé Admin
        User::create([
            'name' => 'Nestlé Admin',
            'email' => 'admin@nestle.lk',
            'password' => Hash::make('admin123'),
            'role' => 'nestle',
            'status' => 'active',
        ]);

        // Sample Distributor
        $distributor = User::create([
            'name' => 'Western Distributor',
            'email' => 'dist@western.lk',
            'password' => Hash::make('password'),
            'role' => 'distributor',
            'territory' => 'Western Province',
            'status' => 'active',
        ]);

        // Sample Wholesaler (Affiliated with Western Distributor)
        $wholesaler = User::create([
            'name' => 'Colombo Wholesalers',
            'email' => 'wholesale@colombo.lk',
            'password' => Hash::make('password'),
            'role' => 'wholesaler',
            'distributor_id' => $distributor->id,
            'region' => 'Colombo',
            'status' => 'active',
        ]);

        // Sample Retailer (Affiliated with Wholesaler)
        User::create([
            'name' => 'Galle Road Grocery',
            'email' => 'retail@galle.lk',
            'password' => Hash::make('password'),
            'role' => 'retailer',
            'wholesaler_id' => $wholesaler->id,
            'distributor_id' => $distributor->id,
            'order_direct' => false,
            'address' => '123, Galle Road, Colombo 03',
            'status' => 'active',
        ]);
        
        // Direct Retailer
        User::create([
            'name' => 'Super Center',
            'email' => 'direct@super.lk',
            'password' => Hash::make('password'),
            'role' => 'retailer',
            'distributor_id' => $distributor->id,
            'order_direct' => true,
            'status' => 'active',
        ]);
    }
}
