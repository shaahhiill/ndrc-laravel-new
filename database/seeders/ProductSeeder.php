<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Beverages
            ['name' => 'MILO 1kg Refill Pack (Distributor Pack)', 'sku' => 'MILO-1KG-D', 'category' => 'Beverages', 'unit' => '1kg', 'description' => 'MILO chocolate malt drink powder - 1kg bulk bag', 'price' => 720.00],
            ['name' => 'NESCAFÉ Classic 500g (Distributor Pack)', 'sku' => 'NESCAFE-500G-D', 'category' => 'Beverages', 'unit' => '500g', 'description' => 'Instant coffee - 500g economy pack', 'price' => 1050.00],
            ['name' => 'Milo Ready-to-Drink 200ml (Case of 24)', 'sku' => 'MILO-RTD-C24', 'category' => 'Beverages', 'unit' => 'Case', 'description' => '24 packs of 200ml Milo RTD', 'price' => 1800.00],
            
            // Noodles
            ['name' => 'MAGGI 2-Minute Noodles (Family Pack - 40pcs)', 'sku' => 'MAGGI-40PK', 'category' => 'Noodles', 'unit' => 'Box', 'description' => 'Bulk box of 40 individual 2-minute noodle packs', 'price' => 1600.00],
            ['name' => 'MAGGI Curry Noodles 8-Pack (Value Pack)', 'sku' => 'MAGGI-8PK-V', 'category' => 'Noodles', 'unit' => '8-pack', 'description' => 'Value pack of 8 curry noodle packs', 'price' => 350.00],
            
            // Dairy
            ['name' => 'Milkmaid Sweetened Condensed Milk (Case of 12)', 'sku' => 'MILKMAID-C12', 'category' => 'Dairy', 'unit' => 'Case', 'description' => '12 cans of 390g sweetened condensed milk', 'price' => 4200.00],
            ['name' => 'Nestlé Everyday Milk Powder 1kg', 'sku' => 'EVERYDAY-1KG', 'category' => 'Dairy', 'unit' => '1kg', 'description' => 'Full cream milk powder for tea/coffee', 'price' => 1450.00],
            ['name' => 'Anchor Full Cream Milk Powder 400g (Bulk Buy)', 'sku' => 'ANCHOR-400G-B', 'category' => 'Dairy', 'unit' => '400g', 'description' => 'Full cream milk powder - 400g pack', 'price' => 580.00],
            
            // Confectionery
            ['name' => 'KitKat 4-Finger (Display Box - 24pcs)', 'sku' => 'KITKAT-BOX24', 'category' => 'Confectionery', 'unit' => 'Box', 'description' => 'Display box containing 24 KitKat 4-finger bars', 'price' => 1100.00],
            ['name' => 'Nestlé Milkybar (Pack of 12)', 'sku' => 'MILKYBAR-P12', 'category' => 'Confectionery', 'unit' => '12-pack', 'description' => 'White chocolate bars pack', 'price' => 480.00],
            
            // Culinary
            ['name' => 'Maggi Coconut Milk Powder 1kg', 'sku' => 'MAGGI-CMP-1KG', 'category' => 'Culinary', 'unit' => '1kg', 'description' => 'Premium coconut milk powder in bulk', 'price' => 1250.00],
            ['name' => 'Maggi Seasoning 200ml (Bottle)', 'sku' => 'MAGGI-SEASON-200', 'category' => 'Culinary', 'unit' => 'Bottle', 'description' => 'All-purpose liquid seasoning', 'price' => 280.00],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            WarehouseStock::create([
                'product_id' => $product->id,
                'total_stock' => 10000,
                'reserved_stock' => 0,
                'reorder_point' => 1000,
            ]);
        }
    }
}
