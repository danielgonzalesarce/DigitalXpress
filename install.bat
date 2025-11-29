@echo off
REM ============================================
REM Script de Instalación Automática - DigitalXpress
REM Para Windows (PowerShell/Batch)
REM ============================================
echo.
echo ========================================
echo   INSTALACION AUTOMATICA - DigitalXpress
echo ========================================
echo.

REM Verificar si PHP está instalado
where php >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP no esta instalado o no esta en el PATH
    echo Por favor instala PHP 8.1 o superior
    pause
    exit /b 1
)

REM Verificar si Composer está instalado
where composer >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer no esta instalado o no esta en el PATH
    echo Por favor instala Composer desde https://getcomposer.org
    pause
    exit /b 1
)

echo [1/8] Verificando dependencias...
php --version
composer --version
echo.

echo [2/8] Instalando dependencias de PHP (Composer)...
call composer install --no-interaction
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Error al instalar dependencias de Composer
    pause
    exit /b 1
)
echo.

echo [3/8] Verificando archivo .env...
if not exist .env (
    echo Creando archivo .env desde .env.example...
    copy .env.example .env
    echo.
    echo ========================================
    echo   CONFIGURACION DE BASE DE DATOS
    echo ========================================
    echo.
    echo Por favor ingresa los datos de tu base de datos PostgreSQL:
    echo.
    set /p DB_HOST="Host de PostgreSQL (default: localhost): "
    if "%DB_HOST%"=="" set DB_HOST=localhost
    
    set /p DB_PORT="Puerto de PostgreSQL (default: 5432): "
    if "%DB_PORT%"=="" set DB_PORT=5432
    
    set /p DB_DATABASE="Nombre de la base de datos (default: digitalxpress): "
    if "%DB_DATABASE%"=="" set DB_DATABASE=digitalxpress
    
    set /p DB_USERNAME="Usuario de PostgreSQL (default: postgres): "
    if "%DB_USERNAME%"=="" set DB_USERNAME=postgres
    
    set /p DB_PASSWORD="Contrasena de PostgreSQL: "
    
    REM Actualizar .env con los valores proporcionados
    powershell -Command "(Get-Content .env) -replace 'DB_HOST=.*', 'DB_HOST=%DB_HOST%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace 'DB_PORT=.*', 'DB_PORT=%DB_PORT%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=%DB_DATABASE%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace 'DB_USERNAME=.*', 'DB_USERNAME=%DB_USERNAME%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=%DB_PASSWORD%' | Set-Content .env"
    
    echo.
    echo Configuracion guardada en .env
) else (
    echo Archivo .env ya existe, usando configuracion existente...
)
echo.

echo [4/8] Generando clave de aplicacion...
php artisan key:generate --force
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Error al generar la clave de aplicacion
    pause
    exit /b 1
)
echo.

echo [5/8] Creando base de datos PostgreSQL...
echo Intentando crear la base de datos...
REM Leer valores del .env
for /f "tokens=2 delims==" %%a in ('findstr /C:"DB_DATABASE=" .env') do set DB_NAME=%%a
for /f "tokens=2 delims==" %%a in ('findstr /C:"DB_USERNAME=" .env') do set DB_USER=%%a
for /f "tokens=2 delims==" %%a in ('findstr /C:"DB_PASSWORD=" .env') do set DB_PASS=%%a
for /f "tokens=2 delims==" %%a in ('findstr /C:"DB_HOST=" .env') do set DB_HOST=%%a
for /f "tokens=2 delims==" %%a in ('findstr /C:"DB_PORT=" .env') do set DB_PORT=%%a

REM Crear base de datos usando psql si está disponible
where psql >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo Creando base de datos usando psql...
    set PGPASSWORD=%DB_PASS%
    psql -h %DB_HOST% -p %DB_PORT% -U %DB_USER% -d postgres -c "CREATE DATABASE %DB_NAME%;" 2>nul
    if %ERRORLEVEL% EQU 0 (
        echo Base de datos creada exitosamente!
    ) else (
        echo Advertencia: No se pudo crear la base de datos automaticamente.
        echo Por favor creala manualmente con: CREATE DATABASE %DB_NAME%;
    )
    set PGPASSWORD=
) else (
    echo psql no encontrado. Por favor crea la base de datos manualmente:
    echo CREATE DATABASE %DB_NAME%;
)
echo.

echo [6/8] Ejecutando migraciones...
php artisan migrate --force
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Error al ejecutar migraciones
    echo Verifica que la base de datos exista y las credenciales sean correctas
    pause
    exit /b 1
)
echo.

echo [7/8] Limpiando cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo [8/8] Creando enlaces simbolicos...
php artisan storage:link
if %ERRORLEVEL% NEQ 0 (
    echo Advertencia: No se pudo crear el enlace simbolico de storage
)
echo.

echo ========================================
echo   INSTALACION COMPLETADA EXITOSAMENTE!
echo ========================================
echo.
echo El proyecto esta listo para usar.
echo.
echo Para iniciar el servidor de desarrollo:
echo   php artisan serve --port=8081
echo.
echo Luego abre tu navegador en:
echo   http://127.0.0.1:8081
echo.
echo Usuarios de prueba:
echo   Admin: admin@digitalxpress.com / password
echo   Cliente: cliente@digitalxpress.com / password
echo.
pause
