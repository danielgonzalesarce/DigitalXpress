# Script de Reorganización de Migraciones
# DigitalXpress - Reorganiza las migraciones de la base de datos

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  REORGANIZACIÓN DE MIGRACIONES" -ForegroundColor Cyan
Write-Host "  DigitalXpress" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar que estamos en el directorio correcto
if (-not (Test-Path "database\migrations")) {
    Write-Host "ERROR: No se encontró la carpeta database/migrations" -ForegroundColor Red
    Write-Host "Asegúrate de ejecutar este script desde la raíz del proyecto" -ForegroundColor Red
    exit 1
}

# Paso 1: Crear carpeta backup si no existe
Write-Host "[1/5] Creando carpeta de backup..." -ForegroundColor Yellow
if (-not (Test-Path "database\migrations\backup")) {
    New-Item -ItemType Directory -Path "database\migrations\backup" | Out-Null
    Write-Host "✓ Carpeta backup creada" -ForegroundColor Green
} else {
    Write-Host "✓ Carpeta backup ya existe" -ForegroundColor Green
}

# Paso 2: Mover migraciones antiguas a backup
Write-Host "[2/5] Moviendo migraciones antiguas a backup..." -ForegroundColor Yellow
$migracionesAntiguas = Get-ChildItem -Path "database\migrations" -Filter "*.php" | Where-Object {
    $_.Name -notlike "*_consolidated.php" -and 
    $_.Name -notlike "2025_01_01_000001_create_cache_table.php" -and
    $_.Name -notlike "2025_01_01_000002_create_jobs_table.php"
}

$movidas = 0
foreach ($migracion in $migracionesAntiguas) {
    $destino = "database\migrations\backup\$($migracion.Name)"
    if (-not (Test-Path $destino)) {
        Move-Item -Path $migracion.FullName -Destination $destino -Force
        $movidas++
    }
}
Write-Host "✓ $movidas migraciones movidas a backup" -ForegroundColor Green

# Paso 3: Renombrar migraciones consolidadas
Write-Host "[3/5] Renombrando migraciones consolidadas..." -ForegroundColor Yellow
$migracionesConsolidadas = Get-ChildItem -Path "database\migrations" -Filter "*_consolidated.php"

$renombradas = 0
foreach ($migracion in $migracionesConsolidadas) {
    $nuevoNombre = $migracion.Name -replace "_consolidated", ""
    $nuevoPath = Join-Path $migracion.DirectoryName $nuevoNombre
    
    if (-not (Test-Path $nuevoPath)) {
        Rename-Item -Path $migracion.FullName -NewName $nuevoNombre
        $renombradas++
    }
}
Write-Host "✓ $renombradas migraciones renombradas" -ForegroundColor Green

# Paso 4: Mostrar resumen
Write-Host "[4/5] Resumen de migraciones..." -ForegroundColor Yellow
$migracionesFinales = Get-ChildItem -Path "database\migrations" -Filter "*.php" | Where-Object {
    $_.Name -notlike "*_consolidated.php"
}
Write-Host "✓ Total de migraciones activas: $($migracionesFinales.Count)" -ForegroundColor Green

# Paso 5: Instrucciones finales
Write-Host "[5/5] Instrucciones finales..." -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  REORGANIZACIÓN COMPLETADA" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "PRÓXIMOS PASOS:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Hacer backup de la base de datos:" -ForegroundColor White
Write-Host "   pg_dump -U postgres digitalxpress_db > backup_antes_reorganizacion.sql" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Limpiar registro de migraciones (OPCIONAL - preserva datos):" -ForegroundColor White
Write-Host "   php artisan tinker" -ForegroundColor Gray
Write-Host "   >>> DB::table('migrations')->truncate();" -ForegroundColor Gray
Write-Host "   >>> exit" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Ejecutar migraciones:" -ForegroundColor White
Write-Host "   php artisan migrate" -ForegroundColor Gray
Write-Host ""
Write-Host "O si quieres recrear todo (ELIMINA DATOS):" -ForegroundColor White
Write-Host "   php artisan migrate:fresh" -ForegroundColor Gray
Write-Host ""
Write-Host "Las migraciones antiguas están en: database/migrations/backup/" -ForegroundColor Cyan
Write-Host ""

