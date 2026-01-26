#!/bin/bash
# Script de inicio rápido para el proyecto Distribuidora

echo "🚀 Iniciando Sistema de Distribuidora..."
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encuentra el archivo artisan"
    echo "Por favor, ejecuta este script desde el directorio raíz del proyecto"
    exit 1
fi

echo "📦 Verificando dependencias..."
if [ ! -d "vendor" ]; then
    echo "⚙️  Instalando dependencias de Composer..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "⚙️  Instalando dependencias de NPM..."
    npm install
fi

echo ""
echo "🔑 Configurando aplicación..."
if [ ! -f ".env" ]; then
    echo "⚙️  Creando archivo .env..."
    cp .env.example .env
    php artisan key:generate
fi

echo ""
echo "🗄️  Base de datos:"
echo "   Usuarios: $(php artisan tinker --execute="echo App\Models\User::count();")"
echo "   Productos: $(php artisan tinker --execute="echo App\Models\Producto::count();")"
echo ""

echo "✅ Sistema listo!"
echo ""
echo "📋 CREDENCIALES DE ACCESO:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "👨‍💼 ADMINISTRADOR:"
echo "   Email: admin@distribuidora.com"
echo "   Password: admin123"
echo ""
echo "👷 TRABAJADOR:"
echo "   Email: trabajador@distribuidora.com"
echo "   Password: trabajador123"
echo ""
echo "👤 CLIENTE:"
echo "   Email: cliente@distribuidora.com"
echo "   Password: cliente123"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🌐 Iniciando servidor en http://localhost:8000"
echo "   Presiona Ctrl+C para detener"
echo ""

php artisan serve
