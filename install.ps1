# ============================================
# Script de Instalación Automática - DigitalXpress
# Para Windows PowerShell
# ============================================

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  INSTALACION AUTOMATICA - DigitalXpress" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar PHP
Write-Host "[1/8] Verificando dependencias..." -ForegroundColor Yellow
try {
    $phpVersion = php --version 2>&1
    Write-Host $phpVersion
} catch {
    Write-Host "[ERROR] PHP no está instalado o no está en el PATH" -ForegroundColor Red
    Write-Host "Por favor instala PHP 8.1 o superior" -ForegroundColor Red
    exit 1
}

# Verificar Composer
try {
    $composerVersion = composer --version 2>&1
    Write-Host $composerVersion
} catch {
    Write-Host "[ERROR] Composer no está instalado o no está en el PATH" -ForegroundColor Red
    Write-Host "Por favor instala Composer desde https://getcomposer.org" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Instalar dependencias de Composer
Write-Host "[2/8] Instalando dependencias de PHP (Composer)..." -ForegroundColor Yellow
composer install --no-interaction
if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERROR] Error al instalar dependencias de Composer" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Verificar y crear archivo .env
Write-Host "[3/8] Verificando archivo .env..." -ForegroundColor Yellow
if (-not (Test-Path .env)) {
    Write-Host "Creando archivo .env desde .env.example..." -ForegroundColor Green
    Copy-Item .env.example .env
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  CONFIGURACION DE BASE DE DATOS" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Por favor ingresa los datos de tu base de datos PostgreSQL:" -ForegroundColor Yellow
    Write-Host ""
    
    $DB_HOST = Read-Host "Host de PostgreSQL (default: localhost)"
    if ([string]::IsNullOrWhiteSpace($DB_HOST)) { $DB_HOST = "localhost" }
    
    $DB_PORT = Read-Host "Puerto de PostgreSQL (default: 5432)"
    if ([string]::IsNullOrWhiteSpace($DB_PORT)) { $DB_PORT = "5432" }
    
    $DB_DATABASE = Read-Host "Nombre de la base de datos (default: digitalxpress)"
    if ([string]::IsNullOrWhiteSpace($DB_DATABASE)) { $DB_DATABASE = "digitalxpress" }
    
    $DB_USERNAME = Read-Host "Usuario de PostgreSQL (default: postgres)"
    if ([string]::IsNullOrWhiteSpace($DB_USERNAME)) { $DB_USERNAME = "postgres" }
    
    $securePassword = Read-Host "Contraseña de PostgreSQL" -AsSecureString
    $DB_PASSWORD = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($securePassword))
    
    # Actualizar .env con los valores proporcionados
    (Get-Content .env) -replace 'DB_HOST=.*', "DB_HOST=$DB_HOST" | Set-Content .env
    (Get-Content .env) -replace 'DB_PORT=.*', "DB_PORT=$DB_PORT" | Set-Content .env
    (Get-Content .env) -replace 'DB_DATABASE=.*', "DB_DATABASE=$DB_DATABASE" | Set-Content .env
    (Get-Content .env) -replace 'DB_USERNAME=.*', "DB_USERNAME=$DB_USERNAME" | Set-Content .env
    (Get-Content .env) -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$DB_PASSWORD" | Set-Content .env
    
    Write-Host ""
    Write-Host "Configuración guardada en .env" -ForegroundColor Green
} else {
    Write-Host "Archivo .env ya existe, usando configuración existente..." -ForegroundColor Green
}
Write-Host ""

# Generar clave de aplicación
Write-Host "[4/8] Generando clave de aplicación..." -ForegroundColor Yellow
php artisan key:generate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERROR] Error al generar la clave de aplicación" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Crear base de datos PostgreSQL
Write-Host "[5/8] Creando base de datos PostgreSQL..." -ForegroundColor Yellow
$envContent = Get-Content .env
$DB_NAME = ($envContent | Select-String "^DB_DATABASE=").ToString().Split('=')[1]
$DB_USER = ($envContent | Select-String "^DB_USERNAME=").ToString().Split('=')[1]
$DB_PASS = ($envContent | Select-String "^DB_PASSWORD=").ToString().Split('=')[1]
$DB_HOST = ($envContent | Select-String "^DB_HOST=").ToString().Split('=')[1]
$DB_PORT = ($envContent | Select-String "^DB_PORT=").ToString().Split('=')[1]

if (Get-Command psql -ErrorAction SilentlyContinue) {
    Write-Host "Creando base de datos usando psql..." -ForegroundColor Green
    $env:PGPASSWORD = $DB_PASS
    psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d postgres -c "CREATE DATABASE $DB_NAME;" 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Base de datos creada exitosamente!" -ForegroundColor Green
    } else {
        Write-Host "Advertencia: No se pudo crear la base de datos automáticamente." -ForegroundColor Yellow
        Write-Host "Por favor créala manualmente con: CREATE DATABASE $DB_NAME;" -ForegroundColor Yellow
    }
    Remove-Item Env:\PGPASSWORD
} else {
    Write-Host "psql no encontrado. Por favor crea la base de datos manualmente:" -ForegroundColor Yellow
    Write-Host "CREATE DATABASE $DB_NAME;" -ForegroundColor Yellow
}
Write-Host ""

# Ejecutar migraciones
Write-Host "[6/8] Ejecutando migraciones..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERROR] Error al ejecutar migraciones" -ForegroundColor Red
    Write-Host "Verifica que la base de datos exista y las credenciales sean correctas" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Limpiar caché
Write-Host "[7/8] Limpiando caché..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
Write-Host ""

# Crear enlaces simbólicos
Write-Host "[8/8] Creando enlaces simbólicos..." -ForegroundColor Yellow
php artisan storage:link
if ($LASTEXITCODE -ne 0) {
    Write-Host "Advertencia: No se pudo crear el enlace simbólico de storage" -ForegroundColor Yellow
}
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "  INSTALACION COMPLETADA EXITOSAMENTE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "El proyecto está listo para usar." -ForegroundColor Green
Write-Host ""
Write-Host "Para iniciar el servidor de desarrollo:" -ForegroundColor Cyan
Write-Host "  php artisan serve --port=8081" -ForegroundColor White
Write-Host ""
Write-Host "Luego abre tu navegador en:" -ForegroundColor Cyan
Write-Host "  http://127.0.0.1:8081" -ForegroundColor White
Write-Host ""
Write-Host "Usuarios de prueba:" -ForegroundColor Cyan
Write-Host "  Admin: admin@digitalxpress.com / password" -ForegroundColor White
Write-Host "  Cliente: cliente@digitalxpress.com / password" -ForegroundColor White
Write-Host ""

