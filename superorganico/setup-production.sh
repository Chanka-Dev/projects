#!/bin/bash
# Script para configurar permisos de Laravel en producción

echo "🔧 Configurando permisos para Laravel SuperOrgánico..."

# Cambiar propietario a www-data (usuario del servidor web)
sudo chown -R www-data:www-data /var/www/superorganico/storage
sudo chown -R www-data:www-data /var/www/superorganico/bootstrap/cache

# Dar permisos de escritura
sudo chmod -R 775 /var/www/superorganico/storage
sudo chmod -R 775 /var/www/superorganico/bootstrap/cache

# Limpiar cachés de Laravel
cd /var/www/superorganico
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Permisos configurados correctamente"
echo "✅ Cachés optimizados para producción"
