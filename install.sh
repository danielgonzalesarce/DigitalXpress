#!/bin/bash

# ============================================
# Script de Instalación Automática - DigitalXpress
# Para Linux/Mac (Bash)
# ============================================

echo ""
echo "========================================"
echo "  INSTALACION AUTOMATICA - DigitalXpress"
echo "========================================"
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para verificar si un comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verificar PHP
echo "[1/8] Verificando dependencias..."
if ! command_exists php; then
    echo -e "${RED}[ERROR] PHP no está instalado${NC}"
    echo "Por favor instala PHP 8.1 o superior"
    exit 1
fi

# Verificar Composer
if ! command_exists composer; then
    echo -e "${RED}[ERROR] Composer no está instalado${NC}"
    echo "Por favor instala Composer desde https://getcomposer.org"
    exit 1
fi

php --version
composer --version
echo ""

# Instalar dependencias de Composer
echo "[2/8] Instalando dependencias de PHP (Composer)..."
composer install --no-interaction
if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR] Error al instalar dependencias de Composer${NC}"
    exit 1
fi
echo ""

# Verificar y crear archivo .env
echo "[3/8] Verificando archivo .env..."
if [ ! -f .env ]; then
    echo "Creando archivo .env desde .env.example..."
    cp .env.example .env
    echo ""
    echo "========================================"
    echo "  CONFIGURACION DE BASE DE DATOS"
    echo "========================================"
    echo ""
    echo "Por favor ingresa los datos de tu base de datos PostgreSQL:"
    echo ""
    
    read -p "Host de PostgreSQL (default: localhost): " DB_HOST
    DB_HOST=${DB_HOST:-localhost}
    
    read -p "Puerto de PostgreSQL (default: 5432): " DB_PORT
    DB_PORT=${DB_PORT:-5432}
    
    read -p "Nombre de la base de datos (default: digitalxpress): " DB_DATABASE
    DB_DATABASE=${DB_DATABASE:-digitalxpress}
    
    read -p "Usuario de PostgreSQL (default: postgres): " DB_USERNAME
    DB_USERNAME=${DB_USERNAME:-postgres}
    
    read -sp "Contraseña de PostgreSQL: " DB_PASSWORD
    echo ""
    
    # Actualizar .env con los valores proporcionados
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        sed -i '' "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
        sed -i '' "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
        sed -i '' "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
        sed -i '' "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
        sed -i '' "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    else
        # Linux
        sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
        sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    fi
    
echo ""
    echo "Configuración guardada en .env"
    else
    echo "Archivo .env ya existe, usando configuración existente..."
fi
echo ""

# Generar clave de aplicación
echo "[4/8] Generando clave de aplicación..."
php artisan key:generate --force
if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR] Error al generar la clave de aplicación${NC}"
    exit 1
fi
echo ""

# Crear base de datos PostgreSQL
echo "[5/8] Creando base de datos PostgreSQL..."
if command_exists psql; then
    # Leer valores del .env
    DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
    DB_USER=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2)
    DB_PASS=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)
    DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2)
    DB_PORT=$(grep "^DB_PORT=" .env | cut -d '=' -f2)
    
    echo "Creando base de datos usando psql..."
    export PGPASSWORD="$DB_PASS"
    psql -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d postgres -c "CREATE DATABASE $DB_NAME;" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Base de datos creada exitosamente!${NC}"
    else
        echo -e "${YELLOW}Advertencia: No se pudo crear la base de datos automáticamente.${NC}"
        echo "Por favor créala manualmente con: CREATE DATABASE $DB_NAME;"
    fi
    unset PGPASSWORD
else
    echo -e "${YELLOW}psql no encontrado. Por favor crea la base de datos manualmente:${NC}"
    DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
    echo "CREATE DATABASE $DB_NAME;"
fi
    echo ""
    
# Ejecutar migraciones
echo "[6/8] Ejecutando migraciones..."
php artisan migrate --force
    if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR] Error al ejecutar migraciones${NC}"
    echo "Verifica que la base de datos exista y las credenciales sean correctas"
    exit 1
fi
echo ""

# Limpiar caché
echo "[7/8] Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo ""

# Crear enlaces simbólicos
echo "[8/8] Creando enlaces simbólicos..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo -e "${YELLOW}Advertencia: No se pudo crear el enlace simbólico de storage${NC}"
fi
echo ""

echo "========================================"
echo -e "  ${GREEN}INSTALACION COMPLETADA EXITOSAMENTE!${NC}"
echo "========================================"
echo ""
echo "El proyecto está listo para usar."
echo ""
echo "Para iniciar el servidor de desarrollo:"
echo "  php artisan serve --port=8081"
echo ""
echo "Luego abre tu navegador en:"
echo "  http://127.0.0.1:8081"
echo ""
echo "Usuarios de prueba:"
echo "  Admin: admin@digitalxpress.com / password"
echo "  Cliente: cliente@digitalxpress.com / password"
echo ""
