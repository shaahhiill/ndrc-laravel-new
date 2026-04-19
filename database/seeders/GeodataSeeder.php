<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;

class GeodataSeeder extends Seeder
{
    public function run(): void
    {
        // Update Distributor
        $distributor = User::where('email', 'dist@western.lk')->first();
        if ($distributor) {
            $distributor->update([
                'latitude' => 6.9271,
                'longitude' => 79.8612,
                'address' => 'Colombo Fort, Colombo'
            ]);
        }

        // Retailer Locations around Colombo
        $retailers = [
            ['email' => 'retail@galle.lk', 'lat' => 6.9044, 'lng' => 79.8519, 'addr' => 'Bambalapitiya, Colombo 04'],
            ['email' => 'direct@super.lk', 'lat' => 6.9360, 'lng' => 79.8448, 'addr' => 'Pettah, Colombo 11'],
        ];

        foreach ($retailers as $r) {
            $user = User::where('email', $r['email'])->first();
            if ($user) {
                $user->update([
                    'latitude' => $r['lat'],
                    'longitude' => $r['lng'],
                    'address' => $r['addr']
                ]);

                // Create some orders if they don't exist
                if (Order::where('retailer_id', $user->id)->count() === 0) {
                    Order::create([
                        'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                        'retailer_id' => $user->id,
                        'distributor_id' => $distributor->id,
                        'status' => 'distributor_confirmed',
                        'order_date' => now(),
                        'total_amount' => rand(5000, 50000),
                    ]);
                }
            }
        }

        // Add more fake retailers for a better map experience
        $extraRetailers = [
            ['name' => 'Kollupitiya Stores', 'lat' => 6.9117, 'lng' => 79.8484, 'territory' => 'Colombo South'],
            ['name' => 'Borella Super', 'lat' => 6.9147, 'lng' => 79.8774, 'territory' => 'Colombo East'],
            ['name' => 'Havelock Food City', 'lat' => 6.8885, 'lng' => 79.8587, 'territory' => 'Colombo South'],
        ];

        foreach ($extraRetailers as $er) {
            $u = User::create([
                'name' => $er['name'],
                'email' => strtolower(str_replace(' ', '.', $er['name'])) . '@test.lk',
                'password' => bcrypt('password'),
                'role' => 'retailer',
                'distributor_id' => $distributor->id,
                'latitude' => $er['lat'],
                'longitude' => $er['lng'],
                'address' => $er['name'] . ', Colombo',
                'territory' => $er['territory'],
                'status' => 'active',
            ]);

            Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'retailer_id' => $u->id,
                'distributor_id' => $distributor->id,
                'status' => 'distributor_confirmed',
                'order_date' => now(),
                'total_amount' => rand(5000, 50000),
            ]);
        }
    }
}
