<?php

/**
 * Modelo Product
 * 
 * Representa un producto en el catálogo de DigitalXpress.
 * 
 * Propiedades principales:
 * - Información básica: nombre, descripción, SKU
 * - Precios: precio regular y precio de oferta
 * - Stock: cantidad disponible y estado
 * - Categorización: pertenece a una categoría
 * - Imágenes: almacenadas como array JSON
 * - Atributos: características adicionales como array JSON
 * - Rating: calificación y cantidad de reseñas
 * 
 * Relaciones:
 * - belongsTo Category: cada producto pertenece a una categoría
 * - hasMany CartItem: puede estar en múltiples carritos
 * - hasMany OrderItem: puede estar en múltiples pedidos
 * - hasMany Favorite: puede ser favorito de múltiples usuarios
 * 
 * Accessors (atributos calculados):
 * - current_price: retorna el precio de oferta si existe, sino el precio regular
 * - is_on_sale: indica si el producto está en oferta
 * - image_url: obtiene la URL de la imagen del producto (con lógica compleja de búsqueda)
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Campos que pueden ser asignados masivamente (mass assignment)
     * Estos campos pueden ser llenados usando create() o update()
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'is_featured',
        'is_active',
        'weight',
        'dimensions',
        'images',
        'attributes',
        'rating',
        'review_count',
        'category_id'
    ];

    /**
     * Conversiones automáticas de tipos de datos
     * Laravel convierte automáticamente estos campos al tipo especificado
     */
    protected $casts = [
        'manage_stock' => 'boolean', // Convertir a booleano
        'in_stock' => 'boolean', // Convertir a booleano
        'is_featured' => 'boolean', // Convertir a booleano
        'is_active' => 'boolean', // Convertir a booleano
        'images' => 'array', // Convertir JSON a array PHP
        'attributes' => 'array', // Convertir JSON a array PHP
        'price' => 'decimal:2', // Convertir a decimal con 2 decimales
        'sale_price' => 'decimal:2', // Convertir a decimal con 2 decimales
        'weight' => 'decimal:2', // Convertir a decimal con 2 decimales
        'rating' => 'decimal:2', // Convertir a decimal con 2 decimales
    ];

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Un producto pertenece a una categoría
     * 
     * @return BelongsTo Relación con el modelo Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Un producto puede estar en múltiples carritos
     * 
     * @return HasMany Relación con el modelo CartItem
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Relación: Un producto puede estar en múltiples pedidos
     * 
     * @return HasMany Relación con el modelo OrderItem
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación: Un producto puede ser favorito de múltiples usuarios
     * 
     * @return HasMany Relación con el modelo Favorite
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * ============================================
     * ACCESSORS (Atributos Calculados)
     * ============================================
     * 
     * Estos métodos permiten acceder a valores calculados
     * como si fueran propiedades del modelo: $product->current_price
     */

    /**
     * Obtener el precio actual del producto
     * Si tiene precio de oferta, retorna ese; sino retorna el precio regular
     * 
     * Uso: $product->current_price
     * 
     * @return float Precio actual (oferta o regular)
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Verificar si el producto está en oferta
     * Un producto está en oferta si tiene sale_price y es menor que el precio regular
     * 
     * Uso: $product->is_on_sale
     * 
     * @return bool True si está en oferta, false en caso contrario
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Obtener la URL de la imagen del producto
     */
    public function getImageUrlAttribute()
    {
        // Si hay imágenes en el campo images (JSON), usar la primera
        if ($this->images && is_array($this->images) && !empty($this->images)) {
            $firstImage = $this->images[0];
            if (str_starts_with($firstImage, 'http')) {
                return $firstImage;
            }
            return asset('storage/' . $firstImage);
        }

        // Buscar imagen en la carpeta public basándose en la categoría
        $categorySlug = $this->category ? $this->category->slug : 'products';
        $imagePath = $this->findProductImage($categorySlug);

        if ($imagePath) {
            return asset($imagePath);
        }

        // Imagen por defecto - usar cualquier imagen disponible
        $defaultPath = public_path('img/products/laptops');
        if (is_dir($defaultPath)) {
            $files = glob($defaultPath . '/*.{webp,jpg,jpeg,png}', GLOB_BRACE);
            if (!empty($files)) {
                return asset('img/products/laptops/' . basename($files[0]));
            }
        }
        
        // Si no hay ninguna imagen, usar un placeholder SVG
        return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect fill="#e5e7eb" width="300" height="200"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="16">Sin imagen</text></svg>');
    }

    /**
     * Buscar imagen del producto en la carpeta public
     */
    private function findProductImage($categorySlug)
    {
        // Mapeo completo de categorías a carpetas de imágenes
        $categoryFolders = [
            // Categorías principales
            'smartphones' => 'phones',
            'celulares' => 'phones',
            'laptops' => 'laptops',
            'audio' => 'accessories',
            'audifonos' => 'accessories',
            'wearables' => 'watches',
            'relojes' => 'watches',
            'gaming' => 'laptops',
            'camaras' => 'cameras',
            'televisores' => 'accessories',
            'mouses' => 'accessories',
            'teclados' => 'accessories',
        ];

        $folder = $categoryFolders[$categorySlug] ?? 'laptops';
        $basePath = public_path("img/products/{$folder}");

        if (!is_dir($basePath)) {
            return null;
        }

        $files = glob($basePath . '/*.{webp,jpg,jpeg,png}', GLOB_BRACE);

        if (empty($files)) {
            return null;
        }

        // Mapeo específico de productos conocidos a imágenes (basado en nombres de archivos reales)
        // Ordenado por especificidad: más específico primero
        $productImageMap = [
            // Phones/Celulares - Mapeo específico
            'samsung galaxy s24 ultra' => ['samsung galaxy s24 ultra', 'gear samsung galaxy s24 plus source julian chokkattu', 'gear samsung galaxy s24 plus', 'gear samsung galaxy s24'],
            'samsung galaxy s24' => ['samsung galaxy s24 ultra', 'gear samsung galaxy s24 plus source julian chokkattu', 'gear samsung galaxy s24 plus', 'gear samsung galaxy s24'],
            'iphone 16 pro' => ['iphone 16 pro', 'iphone 17'],
            'iphone 16 pro max' => ['iphone 16 pro', 'iphone 17'],
            
            // Laptops - Mapeo específico
            'macbook pro m3' => ['apple macbook pro m4 lineup', 'apple macbook pro m4', 'macbook pro'],
            'macbook pro m4' => ['apple macbook pro m4 lineup', 'apple macbook pro m4', 'macbook pro'],
            'macbook air m2' => ['apple macbook pro m4 lineup', 'apple macbook pro m4', 'macbook'],
            'laptop gaming asus rog strix g16' => ['asus rog strix', 'rog strix', 'gamer', 'computadora pc gamer'],
            'asus rog strix g16' => ['asus rog strix', 'rog strix', 'gamer', 'computadora pc gamer'],
            'dell xps 15' => ['dell xps', 'xps'],
            
            // Audífonos/Audio - Mapeo específico
            'sony wh-1000xm5' => ['cuffie sony wh-1000xm5', 'sony wh-1000'],
            'audifonos sony wh-1000xm5' => ['cuffie sony wh-1000xm5', 'sony wh-1000'],
            'airpods pro 3' => ['airpods max 2', 'airpods'],
            'airpods pro' => ['airpods max 2', 'airpods'],
            'airpods max' => ['airpods max 2', 'airpods'],
            
            // Watches/Relojes - Mapeo específico
            'apple watch series 9' => ['apple watch ultra2 e vecchio', 'apple watch ultra2', 'apple watch'],
            'apple watch series' => ['apple watch ultra2 e vecchio', 'apple watch ultra2', 'apple watch'],
            'samsung galaxy watch 6' => ['smartwatch amazfit gtr 3', 'smartwatch xiaomi redmi watch', 'smartwatch'],
            
            // Televisores - Mapeo específico
            'smart tv samsung 55' => ['smart tv samsung 55 4k uhd', 'smart tv samsung 55', 'samsung 55'],
            'smart tv samsung 55 4k uhd' => ['smart tv samsung 55 4k uhd', 'smart tv samsung 55', 'samsung 55'],
            'smart tv lg oled 65' => ['smart tv lg oled 65 4k', 'smart tv lg oled 65', 'lg oled 65'],
            'smart tv lg oled 65 4k' => ['smart tv lg oled 65 4k', 'smart tv lg oled 65', 'lg oled 65'],
            
            // Mouses - Mapeo específico
            'mouse gaming logitech g502' => ['mouse gamer machenike m7 pro', 'mouse gamer thunderobot ml903', 'mouse gamer'],
            'mouse inalambrico logitech mx master 3s' => ['mouse genius ergo 8350s', 'mouse genius ergo', 'mouse'],
            
            // Teclados - Mapeo específico
            'teclado mecanico hyperx alloy origins' => ['teclado gamer genius gx scorpion', 'teclado gamer t-dagger naxox', 'teclado gamer'],
            'teclado inalambrico logitech mx keys' => ['teclado gamer thunderobot mecanico', 'teclado gamer thunderobot', 'teclado gamer'],
            
            // Cámaras - Mapeo específico
            'camara mirrorless sony alpha a7 iii' => ['camara xiaomi smart aw300', 'camara xiaomi smart c300', 'camara xiaomi smart'],
            'camara de accion gopro hero12 black' => ['combo camara gopro hero 12 chdhx', 'combo camara gopro hero 12', 'gopro hero 12'],
        ];

        // Normalizar nombre del producto
        $productName = $this->normalizeName($this->name);
        
        // PRIMERO: Buscar imagen con el mismo nombre exacto del producto
        foreach ($files as $file) {
            $fileName = $this->normalizeName(pathinfo($file, PATHINFO_FILENAME));
            
            // Coincidencia exacta (máxima prioridad)
            if ($fileName === $productName) {
                return "img/products/{$folder}/" . basename($file);
            }
            
            // Si el nombre del archivo contiene el nombre del producto o viceversa
            if (str_contains($fileName, $productName) || str_contains($productName, $fileName)) {
                // Verificar que sea una coincidencia razonable (al menos 70% de palabras)
                $productWords = array_filter(explode(' ', $productName), function($word) {
                    return strlen(trim($word)) > 2;
                });
                $fileWords = array_filter(explode(' ', $fileName), function($word) {
                    return strlen(trim($word)) > 2;
                });
                
                $matchCount = 0;
                foreach ($productWords as $pWord) {
                    foreach ($fileWords as $fWord) {
                        if ($pWord === $fWord || str_contains($fWord, $pWord) || str_contains($pWord, $fWord)) {
                            $matchCount++;
                            break;
                        }
                    }
                }
                $matchRatio = count($productWords) > 0 ? $matchCount / count($productWords) : 0;
                if ($matchRatio >= 0.7) {
                    return "img/products/{$folder}/" . basename($file);
                }
            }
        }
        
        // SEGUNDO: Verificar si hay un mapeo directo (búsqueda flexible)
        foreach ($productImageMap as $productKey => $imageKeys) {
            // Convertir a array si es string
            if (!is_array($imageKeys)) {
                $imageKeys = [$imageKeys];
            }
            
            // Verificar si el nombre del producto contiene la clave o viceversa
            $productMatches = false;
            $productKeyWords = explode(' ', $productKey);
            $productMatchCount = 0;
            foreach ($productKeyWords as $keyWord) {
                if (strlen($keyWord) > 2 && str_contains($productName, $keyWord)) {
                    $productMatchCount++;
                }
            }
            // Si al menos el 60% de las palabras clave coinciden
            if ($productMatchCount >= max(1, count($productKeyWords) * 0.6)) {
                $productMatches = true;
            }
            
            if ($productMatches || str_contains($productName, $productKey) || str_contains($productKey, $productName)) {
                // Ordenar las claves de imagen por prioridad (más específicas primero)
                usort($imageKeys, function($a, $b) {
                    return strlen($b) - strlen($a); // Más largas primero
                });
                
                foreach ($files as $file) {
                    $fileName = $this->normalizeName(pathinfo($file, PATHINFO_FILENAME));
                    
                    // Buscar coincidencias con cada posible nombre de imagen
                    foreach ($imageKeys as $imageKey) {
                        $imageKeyWords = explode(' ', $imageKey);
                        $matchCount = 0;
                        $totalWords = count($imageKeyWords);
                        
                        // Verificar coincidencias exactas primero (más específico)
                        if (str_contains($fileName, $imageKey) || str_contains($imageKey, $fileName)) {
                            return "img/products/{$folder}/" . basename($file);
                        }
                        
                        // Verificar si todas las palabras clave importantes están presentes
                        $importantWords = ['gear', 'samsung', 'galaxy', 's24'];
                        $importantMatchCount = 0;
                        foreach ($importantWords as $importantWord) {
                            if (str_contains($fileName, $importantWord)) {
                                $importantMatchCount++;
                            }
                        }
                        
                        foreach ($imageKeyWords as $keyWord) {
                            if (strlen($keyWord) > 2 && str_contains($fileName, $keyWord)) {
                                $matchCount++;
                            }
                        }
                        
                        // Si tiene las palabras importantes o al menos el 70% de coincidencias
                        $matchRatio = $totalWords > 0 ? $matchCount / $totalWords : 0;
                        if ($importantMatchCount >= 3 || $matchRatio >= 0.7 || ($matchCount >= 2 && $totalWords <= 3)) {
                            return "img/products/{$folder}/" . basename($file);
                        }
                    }
                }
            }
        }
        
        $productWords = $this->extractKeywords($productName);

        $bestMatch = null;
        $bestScore = 0;

        // Buscar la mejor coincidencia usando sistema de puntuación
        foreach ($files as $file) {
            $fileName = $this->normalizeName(pathinfo($file, PATHINFO_FILENAME));
            $fileWords = $this->extractKeywords($fileName);
            
            $score = $this->calculateMatchScore($productWords, $fileWords, $productName, $fileName);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $file;
            }
        }

        // Si hay una buena coincidencia (score > 0.3), usar esa imagen
        if ($bestMatch && $bestScore > 0.3) {
            return "img/products/{$folder}/" . basename($bestMatch);
        }

        // Si no hay buena coincidencia, usar la primera imagen de la carpeta
        return "img/products/{$folder}/" . basename($files[0]);
    }

    /**
     * Normalizar nombre: eliminar caracteres especiales, convertir a minúsculas
     */
    private function normalizeName($name)
    {
        // Convertir a minúsculas
        $name = strtolower($name);
        
        // Reemplazar caracteres especiales comunes por espacios (incluyendo comillas)
        $name = str_replace(['-', '_', '&', '+', '(', ')', '[', ']', '.', ',', ':', ';', '!', '?', '/', '\\', '"', "'", '″', '″'], ' ', $name);
        
        // Reemplazar números seguidos de letras (ej: "m3" -> "m 3" para mejor matching)
        $name = preg_replace('/([a-z])(\d)/', '$1 $2', $name);
        $name = preg_replace('/(\d)([a-z])/', '$1 $2', $name);
        
        // Eliminar espacios múltiples
        $name = preg_replace('/\s+/', ' ', $name);
        
        return trim($name);
    }

    /**
     * Extraer palabras clave relevantes del nombre
     */
    private function extractKeywords($name)
    {
        // Palabras a ignorar
        $stopWords = ['de', 'el', 'la', 'los', 'las', 'y', 'con', 'para', 'por', 'del', 'al', 'un', 'una', 'unos', 'unas'];
        
        $words = explode(' ', $name);
        $keywords = [];
        
        foreach ($words as $word) {
            $word = trim($word);
            // Solo incluir palabras de más de 2 caracteres que no sean stop words
            if (strlen($word) > 2 && !in_array($word, $stopWords)) {
                $keywords[] = $word;
            }
        }
        
        return $keywords;
    }

    /**
     * Calcular puntuación de coincidencia entre producto e imagen
     */
    private function calculateMatchScore($productWords, $fileWords, $productName, $fileName)
    {
        $score = 0;
        $maxScore = 0;

        // Coincidencias exactas de palabras clave (peso alto)
        foreach ($productWords as $productWord) {
            $maxScore += 10;
            foreach ($fileWords as $fileWord) {
                // Coincidencia exacta
                if ($productWord === $fileWord) {
                    $score += 10;
                }
                // Coincidencia parcial (contiene)
                elseif (str_contains($fileWord, $productWord) || str_contains($productWord, $fileWord)) {
                    $score += 5;
                }
            }
        }

        // Coincidencias de subcadenas importantes (marca, modelo)
        $importantTerms = [
            'iphone', 'samsung', 'galaxy', 'macbook', 'apple', 'dell', 'xps', 
            'asus', 'rog', 'strix', 'sony', 'wh-1000', 'wh1000', 'airpods', 
            'watch', 'series', 'gopro', 'hero', 'logitech', 'mx', 'master',
            'hyperx', 'gamer', 'gaming', 'teclado', 'mouse', 'smartwatch',
            'televisor', 'camara', 'mirrorless', 'alpha'
        ];
        
        foreach ($importantTerms as $term) {
            $productHas = str_contains($productName, $term);
            $fileHas = str_contains($fileName, $term);
            
            if ($productHas && $fileHas) {
                $score += 15; // Coincidencia de término importante
            }
        }

        // Coincidencia de números (modelos, versiones)
        preg_match_all('/\d+/', $productName, $productNumbers);
        preg_match_all('/\d+/', $fileName, $fileNumbers);
        
        if (!empty($productNumbers[0]) && !empty($fileNumbers[0])) {
            foreach ($productNumbers[0] as $productNum) {
                foreach ($fileNumbers[0] as $fileNum) {
                    if ($productNum === $fileNum) {
                        $score += 8; // Coincidencia de número
                    }
                }
            }
        }

        // Normalizar score (0-1)
        if ($maxScore > 0) {
            return min($score / max($maxScore, 50), 1.0);
        }

        return 0;
    }
}

