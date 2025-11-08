<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de prueba
        $categories = [
            ['name' => 'Smartphones', 'slug' => 'smartphones', 'description' => 'Teléfonos inteligentes'],
            ['name' => 'Laptops', 'slug' => 'laptops', 'description' => 'Computadoras portátiles'],
            ['name' => 'Audio', 'slug' => 'audio', 'description' => 'Audífonos y parlantes'],
            ['name' => 'Wearables', 'slug' => 'wearables', 'description' => 'Dispositivos portátiles'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Accesorios para gaming'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }

        // Crear productos de prueba
        $products = [
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Smartphone premium con cámara de 200MP',
                'price' => 1199.00,
                'sku' => 'SGS24ULT001',
                'stock_quantity' => 30,
                'category_id' => 1,
                'is_active' => true,
                'in_stock' => true,
            ],
            [
                'name' => 'MacBook Pro M3',
                'slug' => 'macbook-pro-m3',
                'description' => 'Laptop profesional con chip M3',
                'price' => 1999.00,
                'sku' => 'MBPM3001',
                'stock_quantity' => 25,
                'category_id' => 2,
                'is_active' => true,
                'in_stock' => true,
            ],
            [
                'name' => 'Dell XPS 15',
                'slug' => 'dell-xps-15',
                'description' => 'Laptop ultrabook con pantalla 4K',
                'price' => 1599.00,
                'sku' => 'DXP15001',
                'stock_quantity' => 20,
                'category_id' => 2,
                'is_active' => true,
                'in_stock' => true,
            ],
            [
                'name' => 'AirPods Pro 3',
                'slug' => 'airpods-pro-3',
                'description' => 'Audífonos inalámbricos con cancelación de ruido',
                'price' => 249.00,
                'sku' => 'APP3001',
                'stock_quantity' => 100,
                'category_id' => 3,
                'is_active' => true,
                'in_stock' => true,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Audífonos over-ear con cancelación de ruido',
                'price' => 349.00,
                'sku' => 'SWX5001',
                'stock_quantity' => 75,
                'category_id' => 3,
                'is_active' => true,
                'in_stock' => true,
            ],
            [
                'name' => 'Apple Watch Series 9',
                'slug' => 'apple-watch-series-9',
                'description' => 'Smartwatch con GPS y monitor de salud',
                'price' => 399.00,
                'sku' => 'AWS9001',
                'stock_quantity' => 60,
                'category_id' => 4,
                'is_active' => true,
                'in_stock' => true,
            ],
            [
                'name' => 'ASUS ROG Strix G16',
                'slug' => 'asus-rog-strix-g16',
                'description' => 'Laptop gaming con RTX 4060',
                'price' => 1799.00,
                'sku' => 'ARG16001',
                'stock_quantity' => 15,
                'category_id' => 5,
                'is_active' => true,
                'in_stock' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }

        // Crear usuario de prueba
        User::firstOrCreate(
            ['email' => 'test@digitalxpress.com'],
            [
                'name' => 'Usuario de Prueba',
                'email' => 'test@digitalxpress.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Datos de prueba creados exitosamente!');
        $this->command->info('📱 Productos: ' . Product::count());
        $this->command->info('👤 Usuario de prueba: test@digitalxpress.com / password');
    }
}