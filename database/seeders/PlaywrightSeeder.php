<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PlaywrightSeeder extends Seeder
{
    public function run(): void
    {
        // Clean up to ensure a deterministic state
        DB::statement('DELETE FROM delivery_stops');
        DB::statement('DELETE FROM delivery_routes');
        DB::statement('DELETE FROM orders');
        
        // Ensure the Distributor exists
        $distributor = User::updateOrCreate(
            ['email' => 'dist@western.lk'],
            [
                'name' => 'Western Distributor',
                'password' => Hash::make('password'),
                'role' => 'distributor',
                'latitude' => 6.9271,
                'longitude' => 79.8612,
                'address' => 'Colombo Fort, Colombo',
                'status' => 'active',
            ]
        );

        // Ensure the Retailer exists
        $retailer = User::updateOrCreate(
            ['email' => 'retail@galle.lk'],
            [
                'name' => 'Galle Road Grocery',
                'password' => Hash::make('password'),
                'role' => 'retailer',
                'distributor_id' => $distributor->id,
                'latitude' => 6.9044,
                'longitude' => 79.8519,
                'address' => 'Bambalapitiya, Colombo 04',
                'status' => 'active',
            ]
        );

        // Create 3 orders that are ready for the Distributor to optimize
        for ($i = 1; $i <= 3; $i++) {
            Order::create([
                'order_number' => 'TEST-ORD-' . $i . strtoupper(Str::random(4)),
                'retailer_id' => $retailer->id,
                'distributor_id' => $distributor->id,
                'status' => 'distributor_confirmed',
                'order_date' => now(),
                'scheduled_dispatch_date' => now()->addDays(1),
                'total_amount' => 5000 + ($i * 1000),
            ]);
        }
    }
}
