#!/bin/bash
# Script de build para Vercel
set -e

echo "Building assets with Vite..."
npm run build

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet

echo "Creating dist directory for Vercel..."
mkdir -p dist
cp -r public/* dist/

echo "Build completed successfully!"

