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
            // Beverages - Bulk
            ['name' => 'MILO 400g (Carton of 24)', 'sku' => 'MILO-400G-C24', 'category' => 'Beverages', 'unit' => 'Carton', 'description' => 'Standard retail size 400g Milo packs in a bulk carton of 24 units.', 'price' => 12500.00],
            ['name' => 'NESCAFÉ Classic 200g (Carton of 12)', 'sku' => 'NES-200G-C12', 'category' => 'Beverages', 'unit' => 'Carton', 'description' => 'Glass jars of Nescafe Classic, 12 units per carton.', 'price' => 18000.00],
            ['name' => 'Milo RTD 200ml (Case of 24)', 'sku' => 'MILO-RTD-C24', 'category' => 'Beverages', 'unit' => 'Case', 'description' => 'Ready to drink Milo packs, 24 units per shrink-wrapped case.', 'price' => 1920.00],
            ['name' => 'Nestea Lemon 1kg (Bulk Pack of 5)', 'sku' => 'NESTEA-1KG-B5', 'category' => 'Beverages', 'unit' => 'Bunch', 'description' => 'Industrial size 1kg Nestea Lemon powder, bunch of 5 packs.', 'price' => 6500.00],
            ['name' => 'Nespray Everyday 400g (Carton of 18)', 'sku' => 'NESP-400G-C18', 'category' => 'Beverages', 'unit' => 'Carton', 'description' => 'Everyday milk powder 400g packs, 18 units per carton.', 'price' => 9800.00],
            
            // Noodles - Bulk
            ['name' => 'MAGGI 2-Min Curry (Family Box of 60)', 'sku' => 'MAGGI-CUR-B60', 'category' => 'Noodles', 'unit' => 'Box', 'description' => 'Bulk box containing 60 family-size curry noodle packs.', 'price' => 4500.00],
            ['name' => 'MAGGI 2-Min Chicken (Family Box of 60)', 'sku' => 'MAGGI-CHK-B60', 'category' => 'Noodles', 'unit' => 'Box', 'description' => 'Bulk box containing 60 family-size chicken noodle packs.', 'price' => 4500.00],
            ['name' => 'MAGGI Daiya Noodle 75g (Case of 40)', 'sku' => 'MAGGI-DAI-C40', 'category' => 'Noodles', 'unit' => 'Case', 'description' => 'Large case of 40 Daiya noodle individual packs.', 'price' => 2800.00],
            
            // Dairy - Bulk
            ['name' => 'Milkmaid 390g Cans (Case of 24)', 'sku' => 'MILK-390G-C24', 'category' => 'Dairy', 'unit' => 'Case', 'description' => 'Standard Milkmaid sweetened condensed milk, 24 cans per case.', 'price' => 8400.00],
            ['name' => 'Nestlé Milk 1L (Carton of 12)', 'sku' => 'NMILK-1L-C12', 'category' => 'Dairy', 'unit' => 'Carton', 'description' => 'UHT full cream milk 1L cartons, 12 units per shipping carton.', 'price' => 4200.00],
            ['name' => 'Nespray Nutri-Up 200ml (Case of 24)', 'sku' => 'NESP-NU-C24', 'category' => 'Dairy', 'unit' => 'Case', 'description' => 'Nutri-Up ready to drink milk, 24 units per case.', 'price' => 2100.00],
            
            // Confectionery
            ['name' => 'KitKat 2-Finger (Display Box - 48pcs)', 'sku' => 'KITKAT-2F-B48', 'category' => 'Confectionery', 'unit' => 'Box', 'description' => 'Classic 2-finger KitKat bars, 48 units in a display box.', 'price' => 3800.00],
            ['name' => 'KitKat 4-Finger (Display Box - 24pcs)', 'sku' => 'KITKAT-4F-B24', 'category' => 'Confectionery', 'unit' => 'Box', 'description' => 'Standard 4-finger KitKat bars, 24 units in a display box.', 'price' => 2400.00],
            ['name' => 'Nestlé Munch (Jar of 60)', 'sku' => 'MUNCH-JAR60', 'category' => 'Confectionery', 'unit' => 'Jar', 'description' => 'Large display jar containing 60 Munch chocolate wafer bars.', 'price' => 1500.00],
            ['name' => 'Polo Mint (Case of 100 Rolls)', 'sku' => 'POLO-C100', 'category' => 'Confectionery', 'unit' => 'Case', 'description' => 'Bulk shipping case containing 100 rolls of Polo mints.', 'price' => 3500.00],
            
            // Culinary
            ['name' => 'Maggi Coconut Milk Powder 1kg (Carton of 10)', 'sku' => 'MAG-CMP-C10', 'category' => 'Culinary', 'unit' => 'Carton', 'description' => 'Bulk supply of 1kg coconut milk powder, 10 units per carton.', 'price' => 14000.00],
            ['name' => 'Maggi Seasoning 200ml (Case of 12)', 'sku' => 'MAG-SEA-C12', 'category' => 'Culinary', 'unit' => 'Case', 'description' => 'Liquid seasoning bottles, 12 units per case.', 'price' => 3600.00],
            ['name' => 'Maggi Rasamusu 10g (Box of 100)', 'sku' => 'MAG-RS-B100', 'category' => 'Culinary', 'unit' => 'Box', 'description' => 'Display box containing 100 individual Rasamusu sachets.', 'price' => 1800.00],
            
            // Cereals
            ['name' => 'Nestum 500g (Carton of 12)', 'sku' => 'NESTUM-C12', 'category' => 'Cereals', 'unit' => 'Carton', 'description' => 'Health cereal 500g tin/box, 12 units per carton.', 'price' => 9600.00],
            ['name' => 'Koko Krunch 330g (Carton of 12)', 'sku' => 'KOKO-C12', 'category' => 'Cereals', 'unit' => 'Carton', 'description' => 'Breakfast cereal 330g boxes, 12 units per carton.', 'price' => 7800.00],
            
            // Infant Nutrition (Bulk Restricted)
            ['name' => 'Lactogen 1 400g (Case of 6)', 'sku' => 'LACTO1-C6', 'category' => 'Nutrition', 'unit' => 'Case', 'description' => 'Infant formula stage 1, bulk case of 6.', 'price' => 11000.00],
            ['name' => 'NAN GROW 3 800g (Case of 4)', 'sku' => 'NAN3-C4', 'category' => 'Nutrition', 'unit' => 'Case', 'description' => 'Follow up formula for toddlers, case of 4.', 'price' => 16500.00],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);
            
            WarehouseStock::create([
                'product_id' => $product->id,
                'total_stock' => rand(500, 2000),
                'reserved_stock' => 0,
                'reorder_point' => 100,
            ]);
        }
    }
}
