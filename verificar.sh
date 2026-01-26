#!/bin/bash
# Script de verificación para Distribuidora

echo "🔍 Verificando Distribuidora en http://181.188.171.38/distribuidora/"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Verificar Nginx
echo "📌 Estado de Nginx:"
systemctl status nginx | grep -E "Active:|Main PID" | head -2
echo ""

# Verificar permisos
echo "📁 Permisos de directorios críticos:"
ls -ld /var/www/distribuidora/storage | awk '{print "storage: " $1 " " $3":"$4}'
ls -ld /var/www/distribuidora/bootstrap/cache | awk '{print "bootstrap/cache: " $1 " " $3":"$4}'
echo ""

# Verificar acceso local
echo "🌐 Verificando acceso local:"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/distribuidora/)
if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Landing page: OK (HTTP $HTTP_CODE)"
else
    echo "❌ Landing page: ERROR (HTTP $HTTP_CODE)"
fi

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/distribuidora/login)
if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Login page: OK (HTTP $HTTP_CODE)"
else
    echo "❌ Login page: ERROR (HTTP $HTTP_CODE)"
fi
echo ""

# Verificar Laravel
echo "🔧 Estado de Laravel:"
cd /var/www/distribuidora
php artisan --version
echo ""

# Verificar base de datos
echo "💾 Conexión a base de datos:"
php artisan tinker --execute="try { \$count = App\Models\User::count(); echo '✅ Base de datos OK - ' . \$count . ' usuarios encontrados'; } catch (\Exception \$e) { echo '❌ Error: ' . \$e->getMessage(); }"
echo ""
echo ""

echo "📋 URLs de acceso:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌍 Acceso público: http://181.188.171.38/distribuidora/"
echo "🏠 Acceso local:   http://localhost/distribuidora/"
echo ""
echo "🔐 Credenciales:"
echo "   Admin:      admin@distribuidora.com / admin123"
echo "   Trabajador: trabajador@distribuidora.com / trabajador123"
echo "   Cliente:    cliente@distribuidora.com / cliente123"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
