<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Laptops para trabajo, estudio y gaming',
                'sort_order' => 1
            ],
            [
                'name' => 'Relojes',
                'slug' => 'relojes',
                'description' => 'Smartwatches y relojes inteligentes',
                'sort_order' => 2
            ],
            [
                'name' => 'Televisores',
                'slug' => 'televisores',
                'description' => 'Smart TVs y televisores de alta definición',
                'sort_order' => 3
            ],
            [
                'name' => 'Mouses',
                'slug' => 'mouses',
                'description' => 'Mouse gaming y ergonómicos',
                'sort_order' => 4
            ],
            [
                'name' => 'Teclados',
                'slug' => 'teclados',
                'description' => 'Teclados mecánicos y ergonómicos',
                'sort_order' => 5
            ],
            [
                'name' => 'Audífonos',
                'slug' => 'audifonos',
                'description' => 'Audífonos inalámbricos y con cable',
                'sort_order' => 6
            ],
            [
                'name' => 'Celulares',
                'slug' => 'celulares',
                'description' => 'Smartphones y teléfonos móviles',
                'sort_order' => 7
            ],
            [
                'name' => 'Cámaras',
                'slug' => 'camaras',
                'description' => 'Cámaras digitales y de acción',
                'sort_order' => 8
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
