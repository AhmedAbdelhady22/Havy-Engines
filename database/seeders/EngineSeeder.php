<?php

namespace Database\Seeders;

use App\Models\Engine;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EngineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $engines = [
            // Diesel Engines
            [
                'category' => 'Diesel Engines',
                'name' => 'Cummins X15 Performance Series',
                'description' => 'The Cummins X15 Performance Series delivers exceptional power and fuel efficiency for heavy-duty trucking applications. Features advanced combustion technology and integrated engine brake.',
                'short_description' => 'High-performance diesel engine for heavy-duty trucks',
                'price' => 28500.00,
                'sale_price' => null,
                'stock_quantity' => 15,
                'sku' => 'CUM-X15-PS',
                'brand' => 'Cummins',
                'horsepower' => '500 HP',
                'displacement' => '15.0L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => true,
            ],
            [
                'category' => 'Diesel Engines',
                'name' => 'Caterpillar C13 Industrial',
                'description' => 'The CAT C13 is a versatile industrial diesel engine suitable for construction equipment, generators, and heavy machinery. Known for reliability and low maintenance costs.',
                'short_description' => 'Versatile industrial diesel engine',
                'price' => 22000.00,
                'sale_price' => 19800.00,
                'stock_quantity' => 8,
                'sku' => 'CAT-C13-IND',
                'brand' => 'Caterpillar',
                'horsepower' => '520 HP',
                'displacement' => '12.5L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => true,
            ],
            [
                'category' => 'Diesel Engines',
                'name' => 'Detroit DD15 Gen 5',
                'description' => 'The Detroit DD15 Gen 5 represents the pinnacle of diesel engine technology. Features asymmetric turbocharging and optimized combustion for maximum efficiency.',
                'short_description' => 'Next-generation trucking diesel engine',
                'price' => 31000.00,
                'sale_price' => null,
                'stock_quantity' => 5,
                'sku' => 'DET-DD15-G5',
                'brand' => 'Detroit Diesel',
                'horsepower' => '505 HP',
                'displacement' => '14.8L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => false,
            ],

            // Gas Engines
            [
                'category' => 'Gas Engines',
                'name' => 'Ford Godzilla 7.3L V8',
                'description' => 'The Ford Godzilla 7.3L V8 is a powerful gasoline engine designed for heavy-duty trucks and commercial applications. Delivers impressive torque and towing capacity.',
                'short_description' => 'Heavy-duty gasoline V8 engine',
                'price' => 8500.00,
                'sale_price' => null,
                'stock_quantity' => 20,
                'sku' => 'FORD-GOD-73',
                'brand' => 'Ford',
                'horsepower' => '430 HP',
                'displacement' => '7.3L',
                'fuel_type' => 'Gasoline',
                'condition' => 'new',
                'is_featured' => true,
            ],
            [
                'category' => 'Gas Engines',
                'name' => 'GM L8T 6.6L V8',
                'description' => 'General Motors L8T 6.6L V8 gasoline engine offers excellent durability and performance for commercial trucks and fleet vehicles.',
                'short_description' => 'Commercial-grade gasoline V8',
                'price' => 7200.00,
                'sale_price' => 6800.00,
                'stock_quantity' => 12,
                'sku' => 'GM-L8T-66',
                'brand' => 'General Motors',
                'horsepower' => '401 HP',
                'displacement' => '6.6L',
                'fuel_type' => 'Gasoline',
                'condition' => 'new',
                'is_featured' => false,
            ],

            // Marine Engines
            [
                'category' => 'Marine Engines',
                'name' => 'Volvo Penta D13 MH',
                'description' => 'The Volvo Penta D13 MH marine engine is designed for commercial vessels and workboats. Features corrosion-resistant materials and efficient fuel consumption.',
                'short_description' => 'Commercial marine diesel engine',
                'price' => 45000.00,
                'sale_price' => null,
                'stock_quantity' => 3,
                'sku' => 'VP-D13-MH',
                'brand' => 'Volvo Penta',
                'horsepower' => '600 HP',
                'displacement' => '12.8L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => true,
            ],
            [
                'category' => 'Marine Engines',
                'name' => 'Mercury Racing 450R',
                'description' => 'High-performance outboard engine for racing and sport boats. Lightweight design with advanced performance tuning.',
                'short_description' => 'High-performance outboard motor',
                'price' => 38000.00,
                'sale_price' => 35500.00,
                'stock_quantity' => 6,
                'sku' => 'MERC-450R',
                'brand' => 'Mercury Racing',
                'horsepower' => '450 HP',
                'displacement' => '4.6L',
                'fuel_type' => 'Gasoline',
                'condition' => 'new',
                'is_featured' => false,
            ],

            // Industrial Engines
            [
                'category' => 'Industrial Engines',
                'name' => 'John Deere PowerTech 6.8L',
                'description' => 'Reliable industrial engine for generators, pumps, and construction equipment. EPA Tier 4 Final compliant with excellent fuel economy.',
                'short_description' => 'Industrial diesel powerplant',
                'price' => 18500.00,
                'sale_price' => null,
                'stock_quantity' => 10,
                'sku' => 'JD-PT-68',
                'brand' => 'John Deere',
                'horsepower' => '275 HP',
                'displacement' => '6.8L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => false,
            ],
            [
                'category' => 'Industrial Engines',
                'name' => 'Perkins 1206F-E70TA',
                'description' => 'Compact and powerful industrial diesel engine ideal for generator sets and industrial equipment. Known for low operating costs.',
                'short_description' => 'Compact industrial diesel engine',
                'price' => 15000.00,
                'sale_price' => 13500.00,
                'stock_quantity' => 7,
                'sku' => 'PERK-1206F',
                'brand' => 'Perkins',
                'horsepower' => '225 HP',
                'displacement' => '7.0L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => true,
            ],

            // Agricultural Engines
            [
                'category' => 'Agricultural Engines',
                'name' => 'AGCO Power 84 CTA',
                'description' => 'Purpose-built agricultural engine designed for tractors and combine harvesters. Features high torque at low RPM for demanding fieldwork.',
                'short_description' => 'Heavy-duty tractor engine',
                'price' => 24000.00,
                'sale_price' => null,
                'stock_quantity' => 4,
                'sku' => 'AGCO-84CTA',
                'brand' => 'AGCO Power',
                'horsepower' => '340 HP',
                'displacement' => '8.4L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => false,
            ],
            [
                'category' => 'Agricultural Engines',
                'name' => 'FPT Industrial N67',
                'description' => 'Versatile agricultural engine suitable for medium-duty tractors and farming equipment. Excellent power-to-weight ratio.',
                'short_description' => 'Medium-duty agricultural engine',
                'price' => 16500.00,
                'sale_price' => 15200.00,
                'stock_quantity' => 9,
                'sku' => 'FPT-N67',
                'brand' => 'FPT Industrial',
                'horsepower' => '250 HP',
                'displacement' => '6.7L',
                'fuel_type' => 'Diesel',
                'condition' => 'new',
                'is_featured' => false,
            ],
        ];

        foreach ($engines as $engineData) {
            $category = Category::where('name', $engineData['category'])->first();
            
            if ($category) {
                Engine::create([
                    'category_id' => $category->id,
                    'name' => $engineData['name'],
                    'slug' => Str::slug($engineData['name']),
                    'description' => $engineData['description'],
                    'short_description' => $engineData['short_description'],
                    'price' => $engineData['price'],
                    'sale_price' => $engineData['sale_price'],
                    'stock_quantity' => $engineData['stock_quantity'],
                    'sku' => $engineData['sku'],
                    'brand' => $engineData['brand'],
                    'horsepower' => $engineData['horsepower'],
                    'displacement' => $engineData['displacement'],
                    'fuel_type' => $engineData['fuel_type'],
                    'condition' => $engineData['condition'],
                    'is_featured' => $engineData['is_featured'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
