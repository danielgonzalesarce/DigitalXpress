#!/bin/bash

# 🚀 Script de Instalación Automática - DigitalXpress
# Este script instala todas las dependencias necesarias para ejecutar el proyecto

echo "=========================================="
echo "🚀 Instalación de DigitalXpress"
echo "=========================================="
echo ""

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para verificar si un comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verificar PHP
echo -e "${YELLOW}📋 Verificando requisitos...${NC}"
if ! command_exists php; then
    echo -e "${RED}❌ PHP no está instalado. Por favor instala PHP 8.1 o superior.${NC}"
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo -e "${GREEN}✅ PHP encontrado: $PHP_VERSION${NC}"

# Verificar Composer
if ! command_exists composer; then
    echo -e "${RED}❌ Composer no está instalado. Por favor instala Composer.${NC}"
    echo "   Visita: https://getcomposer.org/download/"
    exit 1
fi
echo -e "${GREEN}✅ Composer encontrado${NC}"

# Verificar Node.js
if ! command_exists node; then
    echo -e "${YELLOW}⚠️  Node.js no está instalado. Los assets no se compilarán.${NC}"
    echo "   Visita: https://nodejs.org/"
    NODE_INSTALLED=false
else
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✅ Node.js encontrado: $NODE_VERSION${NC}"
    NODE_INSTALLED=true
fi

# Verificar NPM
if [ "$NODE_INSTALLED" = true ]; then
    if ! command_exists npm; then
        echo -e "${YELLOW}⚠️  NPM no está instalado.${NC}"
        NPM_INSTALLED=false
    else
        NPM_VERSION=$(npm -v)
        echo -e "${GREEN}✅ NPM encontrado: $NPM_VERSION${NC}"
        NPM_INSTALLED=true
    fi
fi

echo ""
echo -e "${YELLOW}📦 Instalando dependencias de PHP...${NC}"
composer install --no-interaction --prefer-dist --optimize-autoloader

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Error al instalar dependencias de PHP${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Dependencias de PHP instaladas${NC}"

# Instalar dependencias de Node.js si está disponible
if [ "$NPM_INSTALLED" = true ]; then
    echo ""
    echo -e "${YELLOW}📦 Instalando dependencias de Node.js...${NC}"
    npm install
    
    if [ $? -ne 0 ]; then
        echo -e "${YELLOW}⚠️  Error al instalar dependencias de Node.js (continuando...)${NC}"
    else
        echo -e "${GREEN}✅ Dependencias de Node.js instaladas${NC}"
    fi
fi

# Copiar archivo .env
echo ""
echo -e "${YELLOW}⚙️  Configurando archivo .env...${NC}"
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${GREEN}✅ Archivo .env creado desde .env.example${NC}"
    else
        echo -e "${YELLOW}⚠️  Archivo .env.example no encontrado${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  El archivo .env ya existe, no se sobrescribirá${NC}"
fi

# Generar clave de aplicación
echo ""
echo -e "${YELLOW}🔑 Generando clave de aplicación...${NC}"
php artisan key:generate --force

if [ $? -ne 0 ]; then
    echo -e "${YELLOW}⚠️  No se pudo generar la clave (puede que .env no esté configurado)${NC}"
else
    echo -e "${GREEN}✅ Clave de aplicación generada${NC}"
fi

# Preguntar sobre la base de datos
echo ""
echo -e "${YELLOW}🗄️  Configuración de Base de Datos${NC}"
echo "¿Deseas ejecutar las migraciones y seeders ahora? (s/n)"
read -r response

if [[ "$response" =~ ^([sS][iI][mM]|[sS])$ ]]; then
    echo ""
    echo -e "${YELLOW}📊 Ejecutando migraciones y seeders...${NC}"
    php artisan migrate:fresh --seed --force
    
    if [ $? -ne 0 ]; then
        echo -e "${RED}❌ Error al ejecutar migraciones${NC}"
        echo "   Asegúrate de configurar la base de datos en el archivo .env"
    else
        echo -e "${GREEN}✅ Base de datos configurada${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Migraciones omitidas. Ejecuta manualmente:${NC}"
    echo "   php artisan migrate:fresh --seed"
fi

# Compilar assets si NPM está disponible
if [ "$NPM_INSTALLED" = true ]; then
    echo ""
    echo -e "${YELLOW}🎨 Compilando assets...${NC}"
    npm run build
    
    if [ $? -ne 0 ]; then
        echo -e "${YELLOW}⚠️  Error al compilar assets (continuando...)${NC}"
    else
        echo -e "${GREEN}✅ Assets compilados${NC}"
    fi
fi

# Limpiar caché
echo ""
echo -e "${YELLOW}🧹 Limpiando caché...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo -e "${GREEN}✅ Caché limpiado${NC}"

echo ""
echo "=========================================="
echo -e "${GREEN}✅ Instalación completada!${NC}"
echo "=========================================="
echo ""
echo "📝 Próximos pasos:"
echo ""
echo "1. Configura tu base de datos en el archivo .env:"
echo "   DB_CONNECTION=pgsql"
echo "   DB_HOST=127.0.0.1"
echo "   DB_PORT=5432"
echo "   DB_DATABASE=digitalxpress"
echo "   DB_USERNAME=tu_usuario"
echo "   DB_PASSWORD=tu_contraseña"
echo ""
echo "2. Si no ejecutaste las migraciones, ejecuta:"
echo "   php artisan migrate:fresh --seed"
echo ""
echo "3. Inicia el servidor de desarrollo:"
echo "   php artisan serve --port=8081"
echo ""
echo "4. Abre tu navegador en:"
echo "   http://127.0.0.1:8081"
echo ""
echo "👤 Usuarios de prueba:"
echo "   Admin: admin@digitalxpress.com / password"
echo "   Cliente: cliente@digitalxpress.com / password"
echo ""
echo "¡Disfruta de DigitalXpress! 🚀"

