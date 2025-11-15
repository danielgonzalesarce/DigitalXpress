@echo off
REM 🚀 Script de Instalación Automática - DigitalXpress (Windows)
REM Este script instala todas las dependencias necesarias para ejecutar el proyecto

echo ==========================================
echo 🚀 Instalación de DigitalXpress
echo ==========================================
echo.

REM Verificar PHP
echo 📋 Verificando requisitos...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP no está instalado. Por favor instala PHP 8.1 o superior.
    pause
    exit /b 1
)
echo ✅ PHP encontrado
php -v

REM Verificar Composer
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer no está instalado. Por favor instala Composer.
    echo    Visita: https://getcomposer.org/download/
    pause
    exit /b 1
)
echo ✅ Composer encontrado
composer --version

REM Verificar Node.js
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  Node.js no está instalado. Los assets no se compilarán.
    echo    Visita: https://nodejs.org/
    set NODE_INSTALLED=false
) else (
    echo ✅ Node.js encontrado
    node --version
    set NODE_INSTALLED=true
)

REM Verificar NPM
if "%NODE_INSTALLED%"=="true" (
    npm --version >nul 2>&1
    if %errorlevel% neq 0 (
        echo ⚠️  NPM no está instalado.
        set NPM_INSTALLED=false
    ) else (
        echo ✅ NPM encontrado
        npm --version
        set NPM_INSTALLED=true
    )
)

echo.
echo 📦 Instalando dependencias de PHP...
composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorlevel% neq 0 (
    echo ❌ Error al instalar dependencias de PHP
    pause
    exit /b 1
)
echo ✅ Dependencias de PHP instaladas

REM Instalar dependencias de Node.js si está disponible
if "%NPM_INSTALLED%"=="true" (
    echo.
    echo 📦 Instalando dependencias de Node.js...
    call npm install
    if %errorlevel% neq 0 (
        echo ⚠️  Error al instalar dependencias de Node.js (continuando...)
    ) else (
        echo ✅ Dependencias de Node.js instaladas
    )
)

REM Copiar archivo .env
echo.
echo ⚙️  Configurando archivo .env...
if not exist .env (
    if exist .env.example (
        copy .env.example .env
        echo ✅ Archivo .env creado desde .env.example
    ) else (
        echo ⚠️  Archivo .env.example no encontrado
    )
) else (
    echo ⚠️  El archivo .env ya existe, no se sobrescribirá
)

REM Generar clave de aplicación
echo.
echo 🔑 Generando clave de aplicación...
php artisan key:generate --force
if %errorlevel% neq 0 (
    echo ⚠️  No se pudo generar la clave (puede que .env no esté configurado)
) else (
    echo ✅ Clave de aplicación generada
)

REM Preguntar sobre la base de datos
echo.
echo 🗄️  Configuración de Base de Datos
set /p MIGRATE="¿Deseas ejecutar las migraciones y seeders ahora? (s/n): "
if /i "%MIGRATE%"=="s" (
    echo.
    echo 📊 Ejecutando migraciones y seeders...
    php artisan migrate:fresh --seed --force
    if %errorlevel% neq 0 (
        echo ❌ Error al ejecutar migraciones
        echo    Asegúrate de configurar la base de datos en el archivo .env
    ) else (
        echo ✅ Base de datos configurada
    )
) else (
    echo ⚠️  Migraciones omitidas. Ejecuta manualmente:
    echo    php artisan migrate:fresh --seed
)

REM Compilar assets si NPM está disponible
if "%NPM_INSTALLED%"=="true" (
    echo.
    echo 🎨 Compilando assets...
    call npm run build
    if %errorlevel% neq 0 (
        echo ⚠️  Error al compilar assets (continuando...)
    ) else (
        echo ✅ Assets compilados
    )
)

REM Limpiar caché
echo.
echo 🧹 Limpiando caché...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo ✅ Caché limpiado

echo.
echo ==========================================
echo ✅ Instalación completada!
echo ==========================================
echo.
echo 📝 Próximos pasos:
echo.
echo 1. Configura tu base de datos en el archivo .env:
echo    DB_CONNECTION=pgsql
echo    DB_HOST=127.0.0.1
echo    DB_PORT=5432
echo    DB_DATABASE=digitalxpress
echo    DB_USERNAME=tu_usuario
echo    DB_PASSWORD=tu_contraseña
echo.
echo 2. Si no ejecutaste las migraciones, ejecuta:
echo    php artisan migrate:fresh --seed
echo.
echo 3. Inicia el servidor de desarrollo:
echo    php artisan serve --port=8081
echo.
echo 4. Abre tu navegador en:
echo    http://127.0.0.1:8081
echo.
echo 👤 Usuarios de prueba:
echo    Admin: admin@digitalxpress.com / password
echo    Cliente: cliente@digitalxpress.com / password
echo.
echo ¡Disfruta de DigitalXpress! 🚀
echo.
pause

