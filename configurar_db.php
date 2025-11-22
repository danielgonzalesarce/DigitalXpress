<?php
/**
 * Script para configurar la base de datos digitalxpress_db en el archivo .env
 * Ejecutar: php configurar_db.php
 */

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ Error: El archivo .env no existe.\n";
    echo "Por favor, copia .env.example a .env primero.\n";
    exit(1);
}

$envContent = file_get_contents($envFile);

// Actualizar configuración de base de datos
$envContent = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=mysql', $envContent);
$envContent = preg_replace('/^DB_HOST=.*/m', 'DB_HOST=127.0.0.1', $envContent);
$envContent = preg_replace('/^DB_PORT=.*/m', 'DB_PORT=3306', $envContent);
$envContent = preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE=digitalxpress_db', $envContent);

// Si no existe la línea, agregarla
if (!preg_match('/^DB_DATABASE=/m', $envContent)) {
    $envContent .= "\nDB_DATABASE=digitalxpress_db\n";
}

file_put_contents($envFile, $envContent);

echo "✅ Configuración actualizada en .env:\n";
echo "   DB_CONNECTION=mysql\n";
echo "   DB_DATABASE=digitalxpress_db\n";
echo "\n";
echo "⚠️  IMPORTANTE: Verifica que DB_USERNAME y DB_PASSWORD estén correctos.\n";
echo "   Si no tienes contraseña, deja DB_PASSWORD vacío.\n";
echo "\n";
echo "Ahora ejecuta: php artisan config:clear && php artisan migrate:fresh --seed\n";

