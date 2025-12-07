<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Diesel Engines',
                'description' => 'Heavy-duty diesel engines for industrial and commercial applications. Known for durability and fuel efficiency.',
                'is_active' => true,
            ],
            [
                'name' => 'Gas Engines',
                'description' => 'Powerful gasoline engines for various heavy-duty applications. Ideal for equipment requiring high RPM.',
                'is_active' => true,
            ],
            [
                'name' => 'Marine Engines',
                'description' => 'Specialized engines designed for boats, ships, and marine vessels. Built to withstand harsh marine environments.',
                'is_active' => true,
            ],
            [
                'name' => 'Industrial Engines',
                'description' => 'Heavy-duty engines for generators, pumps, compressors, and industrial machinery.',
                'is_active' => true,
            ],
            [
                'name' => 'Agricultural Engines',
                'description' => 'Reliable engines for tractors, harvesters, and other agricultural equipment.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => $category['is_active'],
            ]);
        }
    }
}
