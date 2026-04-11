<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class BulkProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing products to ensure clean slate for B2B
        Product::truncate();

        $products = [
            [
                'name' => 'MILO RTD 200ml (Case of 24)',
                'sku' => 'MILO-RTD-C24',
                'category' => 'Beverages',
                'unit' => 'CASE',
                'description' => 'Milo Ready-to-Drink 200ml packs. Ideal for school canteens and retail shops.',
                'price' => 1440.00,
            ],
            [
                'name' => 'MILO 1kg Refill Pack (Carton of 10)',
                'sku' => 'MILO-1KG-C10',
                'category' => 'Beverages',
                'unit' => 'CARTON',
                'description' => 'Milo 1kg family packs in a bulk carton of 10 units.',
                'price' => 10500.00,
            ],
            [
                'name' => 'NESCAFÉ Classic 200g (Carton of 12)',
                'sku' => 'NES-CLA-200G-C12',
                'category' => 'Beverages',
                'unit' => 'CARTON',
                'description' => 'Nescafe Classic 200g jars in a distribution carton of 12.',
                'price' => 16200.00,
            ],
            [
                'name' => 'KOKO KRUNCH 500g (Carton of 12)',
                'sku' => 'KOKO-C12',
                'category' => 'Cereals',
                'unit' => 'CARTON',
                'description' => 'Koko Krunch chocolate cereal 500g boxes in bulk.',
                'price' => 11400.00,
            ],
            [
                'name' => 'CORN FLAKES 500g (Carton of 12)',
                'sku' => 'CF-C12',
                'category' => 'Cereals',
                'unit' => 'CARTON',
                'description' => 'Nestle Corn Flakes 500g in bulk carton.',
                'price' => 9600.00,
            ],
            [
                'name' => 'MAGGI 2-Min Noodles 70g (Crate of 48)',
                'sku' => 'MAGGI-NOD-C48',
                'category' => 'Culinary',
                'unit' => 'CRATE',
                'description' => 'Maggi 2-Minute Noodles 70g packets in a bulk crate of 48.',
                'price' => 2880.00,
            ],
            [
                'name' => 'MAGGI Coconut Milk Powder 1kg (Carton of 6)',
                'sku' => 'MAGGI-CMP-1KG-C6',
                'category' => 'Culinary',
                'unit' => 'CARTON',
                'description' => '1kg bulk packs of Maggi Coconut Milk Powder for food services.',
                'price' => 6900.00,
            ],
            [
                'name' => 'MAGGI Arroz Caldo (Bowl Case of 12)',
                'sku' => 'MAGGI-AC-C12',
                'category' => 'Culinary',
                'unit' => 'CASE',
                'description' => 'Instant rice porridge bowls in a case of 12.',
                'price' => 2160.00,
            ],
            [
                'name' => 'NESTOMALT 400g (Carton of 15)',
                'sku' => 'NMALT-400G-C15',
                'category' => 'Dairy',
                'unit' => 'CARTON',
                'description' => 'Nestomalt malted food drink 400g packs in a carton of 15.',
                'price' => 9750.00,
            ],
            [
                'name' => 'NESPRAY Everyday 400g (Carton of 12)',
                'sku' => 'NES-EVD-400G-C12',
                'category' => 'Dairy',
                'unit' => 'CARTON',
                'description' => 'Nespray milk powder 400g packs in a distribution carton of 12.',
                'price' => 12600.00,
            ],
            [
                'name' => 'KITKAT 4-Finger (Display Box 36)',
                'sku' => 'KKAT-4F-B36',
                'category' => 'Confectionery',
                'unit' => 'BOX',
                'description' => 'KitKat 4-Finger display boxes containing 36 individual bars.',
                'price' => 4500.00,
            ],
            [
                'name' => 'MILKYBAR (Box of 24)',
                'sku' => 'MBAR-B24',
                'category' => 'Confectionery',
                'unit' => 'BOX',
                'description' => 'Nestle Milkybar white chocolate bars box.',
                'price' => 2400.00,
            ],
            [
                'name' => 'CEREGROW 300g (Carton of 10)',
                'sku' => 'CGROW-300G-C10',
                'category' => 'Dairy',
                'unit' => 'CARTON',
                'description' => 'Nestle Ceregrow 300g packs in a carton of 10.',
                'price' => 8500.00,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
