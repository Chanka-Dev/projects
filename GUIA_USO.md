# 🎯 Guía de Uso del Sistema de Distribuidora

## ✅ Sistema Implementado

Se ha estructurado completamente el proyecto con un sistema de **3 roles diferenciados**:

### 1. 👤 CLIENTE
- **Registro**: Auto-registro público desde `/register`
- **Acceso**: Login común en `/login`
- **Dashboard**: Catálogo público de productos
- **Funcionalidades**:
  - Ver productos disponibles
  - Agregar productos al carrito
  - Realizar pedidos
  - **NO** tiene acceso a AdminLTE

### 2. 👷 TRABAJADOR  
- **Registro**: Solo el administrador puede crear cuentas de trabajador
- **Acceso**: Login común en `/login`
- **Dashboard**: Panel AdminLTE en `/trabajador/dashboard`
- **Funcionalidades**:
  - Ver estadísticas de pedidos
  - Gestionar pedidos (confirmar/completar)
  - **NO** puede crear productos ni usuarios

### 3. 👨‍💼 ADMINISTRADOR
- **Registro**: Solo otro administrador puede crear cuentas de administrador
- **Acceso**: Login común en `/login`
- **Dashboard**: Panel AdminLTE completo en `/admin/dashboard`
- **Funcionalidades**:
  - Todo lo que puede hacer un trabajador
  - CRUD de productos
  - CRUD de usuarios del sistema (trabajadores y administradores)
  - Ver clientes registrados
  - Estadísticas completas

## 🔑 Credenciales de Acceso

```
ADMINISTRADOR:
Email: admin@distribuidora.com
Password: admin123

TRABAJADOR:
Email: trabajador@distribuidora.com
Password: trabajador123

CLIENTE:
Email: cliente@distribuidora.com
Password: cliente123
```

## 🚀 Cómo Usar

### Iniciar el Servidor
```bash
cd /var/www/distribuidora
php artisan serve
```

### Acceder al Sistema
1. Abre tu navegador en `http://localhost:8000`
2. Verás el **catálogo público**
3. Haz clic en "Iniciar Sesión"
4. Usa cualquiera de las credenciales arriba
5. Serás redirigido automáticamente según tu rol

## 📋 Rutas Principales

### Públicas (sin login)
- `/` - Catálogo de productos
- `/login` - Iniciar sesión
- `/register` - Registro de clientes

### Clientes (requiere login como cliente)
- `/carrito` - Ver carrito
- `/pedido/confirmar` - Confirmar pedido

### Trabajadores (requiere login como trabajador o admin)
- `/trabajador/dashboard` - Dashboard de trabajador
- `/trabajador/pedidos` - Gestión de pedidos

### Administradores (requiere login como admin)
- `/admin/dashboard` - Dashboard administrativo
- `/admin/productos` - Gestión de productos
- `/admin/productos/create` - Crear nuevo producto
- `/admin/usuarios` - Gestión de usuarios del sistema
- `/admin/usuarios/create` - Crear nuevo usuario (trabajador/admin)
- `/admin/clientes` - Lista de clientes

## 🛠️ Cambios Implementados

### Base de Datos
✅ Migración para agregar campo `role` a la tabla `users`
✅ Valores posibles: 'cliente', 'trabajador', 'administrador'
✅ Seeder con usuarios de prueba

### Autenticación
✅ Laravel Breeze instalado y configurado
✅ Login único para todos los usuarios
✅ Registro público solo crea usuarios tipo "cliente"
✅ Middleware `CheckRole` para proteger rutas

### Vistas
✅ Dashboard público (Bootstrap 5) para clientes y visitantes
✅ Dashboard AdminLTE para trabajadores
✅ Dashboard AdminLTE completo para administradores
✅ CRUD de usuarios con formularios

### Controladores
✅ `ProductoController` - CRUD de productos con subida de imágenes
✅ `DashboardController` - Redirección según rol
✅ `AdminDashboardController` - Dashboard de admin
✅ `TrabajadorDashboardController` - Dashboard de trabajador
✅ `UsuarioController` - CRUD de usuarios del sistema

### Seguridad
✅ Gates definidos: `isAdministrador`, `isTrabajador`, `isCliente`
✅ Middleware de roles aplicado a todas las rutas sensibles
✅ Menú de AdminLTE con permisos según rol

## 🎨 Interfaz

- **Clientes**: Interfaz limpia con Bootstrap 5, sin AdminLTE
- **Trabajadores/Administradores**: AdminLTE 3 con menú lateral

## 📝 Próximos Pasos Sugeridos

1. **Agregar más productos** desde `/admin/productos/create`
2. **Crear más trabajadores** desde `/admin/usuarios/create`
3. **Verificar flujo de ventas** realizando pedidos de prueba

## 🐛 Debugging

Si algo no funciona:

```bash
# Limpiar caché
php artisan optimize:clear

# Ver logs
tail -f storage/logs/laravel.log

# Verificar rutas
php artisan route:list

# Verificar usuarios
php artisan tinker
>>> App\Models\User::all()
```

## 📧 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminDashboardController.php
│   │   │   └── UsuarioController.php
│   │   ├── Trabajador/
│   │   │   └── TrabajadorDashboardController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   └── User.php (con métodos isAdministrador, isTrabajador, isCliente)
└── Providers/
    └── AuthServiceProvider.php (con Gates)

resources/views/
├── layouts/
│   └── public.blade.php (para clientes)
├── dashboard/
│   ├── public.blade.php (catálogo)
│   ├── admin.blade.php
│   └── trabajador.blade.php
└── admin/usuarios/ (CRUD de usuarios)
```

---

¡El sistema está listo para usar! 🎉
