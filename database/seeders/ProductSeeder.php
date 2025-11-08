<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Laptops
            [
                'name' => 'MacBook Air M2',
                'slug' => 'macbook-air-m2',
                'description' => 'Ultraportátil con chip M2, pantalla Liquid Retina de 13.6 pulgadas, 8GB RAM y 256GB SSD.',
                'short_description' => 'Diseño elegante y rendimiento excepcional',
                'price' => 1199.00,
                'sku' => 'MBA-M2-001',
                'stock_quantity' => 20,
                'category_slug' => 'laptops',
                'is_featured' => true,
                'rating' => 4.8,
                'review_count' => 150,
                'attributes' => [
                    'pantalla' => '13.6" Liquid Retina (2560 x 1664)',
                    'procesador' => 'Apple M2 (8-core CPU, 10-core GPU)',
                    'memoria_ram' => '8 GB / 16 GB / 24 GB',
                    'almacenamiento' => '256 GB / 512 GB / 1 TB / 2 TB SSD',
                    'graficos' => 'GPU integrada de 10 núcleos',
                    'bateria' => 'Hasta 18 horas',
                    'sistema_operativo' => 'macOS',
                    'puertos' => '2x Thunderbolt 4, 3.5mm jack',
                    'peso' => '1.24 kg',
                    'dimensiones' => '30.41 x 21.5 x 1.13 cm'
                ]
            ],
            [
                'name' => 'Laptop Gaming ASUS ROG Strix G16',
                'slug' => 'laptop-gaming-asus-rog-strix-g16',
                'description' => 'Laptop gaming con RTX 4070, Intel Core i7-13650HX y pantalla 165Hz para máximo rendimiento.',
                'short_description' => 'Laptop gaming con RTX 4070',
                'price' => 1799.00,
                'sku' => 'ASG16-001',
                'stock_quantity' => 15,
                'category_slug' => 'laptops',
                'is_featured' => true,
                'rating' => 4.7,
                'review_count' => 67,
                'attributes' => [
                    'pantalla' => '16" FHD 165Hz IPS',
                    'procesador' => 'Intel Core i7-13650HX',
                    'memoria_ram' => '16 GB DDR5',
                    'almacenamiento' => '512 GB / 1 TB NVMe SSD',
                    'tarjeta_grafica' => 'NVIDIA RTX 4070 8GB',
                    'bateria' => '90 Wh',
                    'sistema_operativo' => 'Windows 11',
                    'puertos' => 'USB-C, USB-A, HDMI, Ethernet',
                    'peso' => '2.5 kg',
                    'refrigeracion' => 'Sistema de refrigeración avanzado'
                ]
            ],

            // Relojes
            [
                'name' => 'Apple Watch Series 9',
                'slug' => 'apple-watch-series-9',
                'description' => 'Smartwatch con chip S9, pantalla más brillante y nuevas funciones de salud avanzadas.',
                'short_description' => 'Smartwatch con chip S9',
                'price' => 399.00,
                'sku' => 'AWS9-001',
                'stock_quantity' => 28,
                'category_slug' => 'relojes',
                'is_featured' => true,
                'rating' => 4.6,
                'review_count' => 203,
                'attributes' => [
                    'pantalla' => 'Always-On Retina LTPO OLED',
                    'procesador' => 'Apple S9 SiP',
                    'almacenamiento' => '64 GB',
                    'bateria' => 'Hasta 18 horas',
                    'resistencia_agua' => 'WR50 (hasta 50m)',
                    'sensores' => 'ECG, Oxímetro, Acelerómetro, Giroscopio',
                    'conectividad' => 'GPS, Bluetooth 5.3, Wi-Fi',
                    'sistema_operativo' => 'watchOS 10',
                    'material' => 'Aluminio o Acero inoxidable',
                    'tamaño' => '41mm o 45mm'
                ]
            ],
            [
                'name' => 'Samsung Galaxy Watch 6',
                'slug' => 'samsung-galaxy-watch-6',
                'description' => 'Reloj inteligente con seguimiento de fitness, monitoreo de sueño y diseño personalizable.',
                'short_description' => 'Reloj inteligente Samsung',
                'price' => 299.00,
                'sku' => 'SGW6-001',
                'stock_quantity' => 25,
                'category_slug' => 'relojes',
                'rating' => 4.4,
                'review_count' => 110
            ],

            // Televisores
            [
                'name' => 'Smart TV Samsung 55" 4K UHD',
                'slug' => 'smart-tv-samsung-55-4k-uhd',
                'description' => 'Televisor inteligente de 55 pulgadas con resolución 4K UHD, HDR y sistema operativo Tizen.',
                'short_description' => 'Smart TV Samsung 55" 4K',
                'price' => 699.00,
                'sku' => 'SMTV55-001',
                'stock_quantity' => 12,
                'category_slug' => 'televisores',
                'is_featured' => true,
                'rating' => 4.7,
                'review_count' => 90,
                'attributes' => [
                    'tamaño_pantalla' => '55 pulgadas',
                    'resolucion' => '4K UHD (3840 x 2160)',
                    'tecnologia_pantalla' => 'QLED',
                    'hdr' => 'HDR10, HDR10+, HLG',
                    'procesador' => 'Crystal Processor 4K',
                    'sistema_operativo' => 'Tizen OS',
                    'puertos' => '4x HDMI 2.1, 2x USB, Ethernet',
                    'conectividad' => 'Wi-Fi 6, Bluetooth 5.2',
                    'audio' => 'Dolby Atmos, 2.0.2 canales',
                    'consumo_energia' => 'Clase A'
                ]
            ],
            [
                'name' => 'Smart TV LG OLED 65" 4K',
                'slug' => 'smart-tv-lg-oled-65-4k',
                'description' => 'Televisor OLED de 65 pulgadas con negros perfectos, colores intensos y procesador α9 Gen5 AI.',
                'short_description' => 'Smart TV LG OLED 65" 4K',
                'price' => 1899.00,
                'sku' => 'LGOLED65-001',
                'stock_quantity' => 8,
                'category_slug' => 'televisores',
                'is_featured' => true,
                'rating' => 4.9,
                'review_count' => 70,
                'attributes' => [
                    'tamaño_pantalla' => '65 pulgadas',
                    'resolucion' => '4K UHD (3840 x 2160)',
                    'tecnologia_pantalla' => 'OLED evo',
                    'hdr' => 'HDR10, Dolby Vision, HLG',
                    'procesador' => 'α9 Gen5 AI Processor',
                    'sistema_operativo' => 'webOS 23',
                    'puertos' => '4x HDMI 2.1, 3x USB, Ethernet',
                    'conectividad' => 'Wi-Fi 6E, Bluetooth 5.0',
                    'audio' => 'Dolby Atmos, 2.2.2 canales (40W)',
                    'gaming' => '120Hz, VRR, ALLM, G-Sync'
                ]
            ],

            // Mouses
            [
                'name' => 'Mouse Gaming Logitech G502 HERO',
                'slug' => 'mouse-gaming-logitech-g502-hero',
                'description' => 'Mouse gaming de alto rendimiento con sensor HERO 25K, 11 botones programables y peso ajustable.',
                'short_description' => 'Mouse gaming Logitech G502',
                'price' => 79.99,
                'sku' => 'LGG502-001',
                'stock_quantity' => 50,
                'category_slug' => 'mouses',
                'is_featured' => true,
                'rating' => 4.8,
                'review_count' => 300
            ],
            [
                'name' => 'Mouse Inalámbrico Logitech MX Master 3S',
                'slug' => 'mouse-inalambrico-logitech-mx-master-3s',
                'description' => 'Mouse ergonómico avanzado con seguimiento Darkfield de 8000 DPI, desplazamiento MagSpeed y botones personalizables.',
                'short_description' => 'Mouse inalámbrico Logitech MX Master 3S',
                'price' => 99.99,
                'sku' => 'LGMXM3S-001',
                'stock_quantity' => 40,
                'category_slug' => 'mouses',
                'rating' => 4.7,
                'review_count' => 250
            ],

            // Teclados
            [
                'name' => 'Teclado Mecánico HyperX Alloy Origins',
                'slug' => 'teclado-mecanico-hyperx-alloy-origins',
                'description' => 'Teclado mecánico compacto con switches HyperX Red, iluminación RGB y estructura de aluminio.',
                'short_description' => 'Teclado mecánico HyperX',
                'price' => 109.99,
                'sku' => 'HXAO-001',
                'stock_quantity' => 30,
                'category_slug' => 'teclados',
                'is_featured' => true,
                'rating' => 4.6,
                'review_count' => 180
            ],
            [
                'name' => 'Teclado Inalámbrico Logitech MX Keys',
                'slug' => 'teclado-inalambrico-logitech-mx-keys',
                'description' => 'Teclado inalámbrico premium con teclas esféricas cóncavas, retroiluminación inteligente y diseño elegante.',
                'short_description' => 'Teclado inalámbrico Logitech MX Keys',
                'price' => 119.99,
                'sku' => 'LGMXK-001',
                'stock_quantity' => 25,
                'category_slug' => 'teclados',
                'rating' => 4.7,
                'review_count' => 210
            ],

            // Audífonos
            [
                'name' => 'Audífonos Sony WH-1000XM5',
                'slug' => 'audifonos-sony-wh-1000xm5',
                'description' => 'Audífonos con cancelación de ruido líder en la industria, sonido de alta resolución y llamadas nítidas.',
                'short_description' => 'Audífonos Sony WH-1000XM5',
                'price' => 349.00,
                'sku' => 'SNWH1KM5-001',
                'stock_quantity' => 20,
                'category_slug' => 'audifonos',
                'is_featured' => true,
                'rating' => 4.9,
                'review_count' => 400,
                'attributes' => [
                    'tipo' => 'Over-ear inalámbricos',
                    'cancelacion_ruido' => 'ANC (Active Noise Cancelling)',
                    'bateria' => 'Hasta 30 horas (con ANC)',
                    'carga_rapida' => '3 minutos = 3 horas',
                    'conectividad' => 'Bluetooth 5.2, NFC',
                    'codec_audio' => 'LDAC, AAC, SBC',
                    'respuesta_frecuencia' => '4 Hz - 40 kHz',
                    'microfonos' => '8 micrófonos con cancelación de ruido',
                    'peso' => '250 g',
                    'compatibilidad' => 'Android, iOS, PC'
                ]
            ],
            [
                'name' => 'AirPods Pro 3',
                'slug' => 'airpods-pro-3',
                'description' => 'Audífonos inalámbricos con cancelación adaptativa de ruido y audio espacial personalizado.',
                'short_description' => 'AirPods Pro 3',
                'price' => 249.00,
                'sku' => 'APP3-001',
                'stock_quantity' => 35,
                'category_slug' => 'audifonos',
                'is_featured' => true,
                'rating' => 4.5,
                'review_count' => 128,
                'attributes' => [
                    'tipo' => 'In-ear inalámbricos',
                    'cancelacion_ruido' => 'Cancelación adaptativa activa',
                    'bateria' => 'Hasta 6 horas (con estuche 30 horas)',
                    'carga_inalambrica' => 'MagSafe y Qi',
                    'conectividad' => 'Bluetooth 5.3, Chip H2',
                    'audio_espacial' => 'Sí, con seguimiento de cabeza',
                    'resistencia_agua' => 'IPX4',
                    'microfonos' => 'Micrófonos con cancelación de ruido',
                    'peso' => '5.4 g (cada uno)',
                    'compatibilidad' => 'iOS, iPadOS, macOS'
                ]
            ],

            // Celulares
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Smartphone Android con cámara de 200MP, S Pen integrado y procesador Snapdragon 8 Gen 3.',
                'short_description' => 'Samsung Galaxy S24 Ultra',
                'price' => 1199.00,
                'sku' => 'SGS24U-001',
                'stock_quantity' => 22,
                'category_slug' => 'celulares',
                'is_featured' => true,
                'rating' => 4.7,
                'review_count' => 450,
                'attributes' => [
                    'pantalla' => '6.8" Dynamic AMOLED 2X 120Hz',
                    'procesador' => 'Snapdragon 8 Gen 3',
                    'memoria_ram' => '12 GB',
                    'almacenamiento' => '256 GB / 512 GB / 1 TB',
                    'camara_principal' => '200 MP + 50 MP + 12 MP',
                    'camara_frontal' => '12 MP',
                    'bateria' => '5000 mAh',
                    'sistema_operativo' => 'Android 14',
                    'resistencia_agua' => 'IP68',
                    's_pen' => 'Incluido'
                ]
            ],
            [
                'name' => 'iPhone 16 Pro',
                'slug' => 'iphone-16-pro',
                'description' => 'El iPhone más avanzado con chip A18 Pro, sistema de cámara Pro mejorado y Dynamic Island.',
                'short_description' => 'iPhone 16 Pro con chip A18 Pro',
                'price' => 1299.00,
                'sku' => 'IPH16PRO-001',
                'stock_quantity' => 35,
                'category_slug' => 'celulares',
                'is_featured' => true,
                'rating' => 4.8,
                'review_count' => 320,
                'attributes' => [
                    'pantalla' => '6.3" Super Retina XDR OLED',
                    'procesador' => 'Apple A18 Pro',
                    'memoria_ram' => '8 GB',
                    'almacenamiento' => '128 GB / 256 GB / 512 GB / 1 TB',
                    'camara_principal' => '48 MP + 12 MP + 12 MP',
                    'camara_frontal' => '12 MP TrueDepth',
                    'bateria' => 'Hasta 23 horas de video',
                    'sistema_operativo' => 'iOS 18',
                    'resistencia_agua' => 'IP68',
                    'carga_inalambrica' => 'MagSafe y Qi'
                ]
            ],

            // Cámaras
            [
                'name' => 'Cámara Mirrorless Sony Alpha a7 III',
                'slug' => 'camara-mirrorless-sony-alpha-a7-iii',
                'description' => 'Cámara mirrorless full-frame con sensor de 24.2MP, enfoque automático rápido y grabación de video 4K.',
                'short_description' => 'Cámara Sony Alpha a7 III',
                'price' => 1999.00,
                'sku' => 'SNA7III-001',
                'stock_quantity' => 8,
                'category_slug' => 'camaras',
                'is_featured' => true,
                'rating' => 4.9,
                'review_count' => 120
            ],
            [
                'name' => 'Cámara de Acción GoPro HERO12 Black',
                'slug' => 'camara-de-accion-gopro-hero12-black',
                'description' => 'Cámara de acción con video 5.3K, estabilización HyperSmooth 6.0 y resistencia al agua.',
                'short_description' => 'Cámara GoPro HERO12 Black',
                'price' => 399.00,
                'sku' => 'GPH12B-001',
                'stock_quantity' => 28,
                'category_slug' => 'camaras',
                'rating' => 4.6,
                'review_count' => 190
            ]
        ];

        foreach ($products as $productData) {
            $category = Category::where('slug', $productData['category_slug'])->first();
            
            if ($category) {
                unset($productData['category_slug']);
                $productData['category_id'] = $category->id;
                $productData['slug'] = Str::slug($productData['slug']);
                
                Product::create($productData);
            }
        }
    }
}