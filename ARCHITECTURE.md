# Arquitectura del Proyecto Distribuidora

Este documento sirve como "memoria" del proyecto, detallando la arquitectura técnica, estructura de datos y flujos principales para facilitar el desarrollo y mantenimiento continuo.

**Última actualización**: 2026-01-21

## 1. Visión General
El proyecto es un sistema de gestión para una distribuidora que maneja tres roles principales: **Clientes**, **Trabajadores** y **Administradores**. Permite la realización de pedidos en línea, gestión de inventario y administración de usuarios.

## 2. Stack Tecnológico

### Backend
- **Framework**: Laravel 10.x
- **Lenguaje**: PHP 8.1+
- **Base de Datos**: MySQL/MariaDB
- **Autenticación**: Laravel Breeze + Sanctum
- **Roles/Permisos**: Implementación nativa con Gates y Middleware personalizado (`CheckRole`).

### Frontend
- **Framework JS**: Alpine.js (ligero, para interactividad)
- **CSS**: Tailwind CSS v3.x (configurado con PostCSS y Autoprefixer)
- **Build Tool**: Vite
- **Plantillas**: Laravel Blade
- **Componentes**: AdminLTE 3 (para dashboard admin/trabajador) y estilos personalizados para el catálogo público.

## 3. Estructura de Directorios Clave

```
/var/www/distribuidora/
├── app/
│   ├── Http/Controllers/       # Controladores divididos por funcionalidad (Admin, Trabajador, Público)
│   ├── Models/                 # Modelos Eloquent (User, Pedido, Producto, etc.)
│   └── View/Components/        # Componentes Blade
├── database/
│   ├── migrations/             # Definiciones de esquema de BD
│   └── seeders/                # Datos iniciales (AdminUserSeeder, etc.)
├── resources/
│   ├── css/                    # Estilos (app.css con directivas Tailwind)
│   ├── js/                     # Scripts (app.js, bootstrap.js)
│   └── views/                  # Plantillas Blade
│       ├── layouts/            # Layouts base (app, guest, public)
│       ├── dashboard/          # Vistas del panel de control
│       ├── admin/              # Vistas exclusivas de administradores
│       └── trabajador/         # Vistas exclusivas de trabajadores
└── routes/
    ├── web.php                 # Rutas principales y lógica de enrutamiento por roles
    └── auth.php                # Rutas de autenticación (Breeze)
```

## 4. Esquema de Base de Datos

Las tablas principales y sus relaciones son:

- **users**: Usuarios del sistema.
    - `role`: Enum (`cliente`, `trabajador`, `administrador`).
    - `personal_info`: Detalles adicionales (agregado en migraciones recientes).
- **productos**: Catálogo de ítems.
    - Campos estándar: nombre, precio, stock, descripción.
    - `image_path`: Ruta de almacenamiento de la imagen del producto (public/products).
- **pedidos**: Órdenes realizadas.
    - `user_id`: Relación con el usuario que creó el pedido.
    - `cliente_id`: (Legacy/Opcional) Referencia a tabla clientes separada si existe.
    - `estado`: Enum (`pendiente`, `confirmado`, `completado`, `cancelado`).
    - `mensaje_whatsapp`: Enlace generado para comunicación.
- **pedido_productos**: Tabla pivote para los ítems de cada pedido.
- **clientes**: Información específica de clientes (si se separa de users).

## 5. Control de Acceso y Roles

La seguridad se maneja a través de Middleware en `routes/web.php`:

| Rol | Middleware | Acceso |
|-----|------------|--------|
| **Público** | Ninguno | Catálogo (`/`), Login, Registro |
| **Cliente** | `auth`, `role:cliente` | Carrito (`/carrito`), Crear Pedidos |
| **Trabajador** | `auth`, `role:trabajador` | Dashboard Trabajador (`/trabajador/*`), Gestión de Estado de Pedidos |
| **Administrador** | `auth`, `role:administrador` | Dashboard Admin (`/admin/*`), CRUD Completos (Productos, Usuarios) |

**Lógica de Redirección**:
El login redirige automáticamente al dashboard correspondiente según el rol del usuario (`DashboardController`).

## 6. Flujos Principales

1.  **Flujo de Compra (Cliente)**:
    - Usuario visita catálogo (`/`).
    - Agrega productos al carrito (almacenado en sesión/BD).
    - Procede al checkout (`/pedido/confirmar`).
    - Se genera el pedido en estado `pendiente`.

2.  **Flujo de Atención (Trabajador)**:
    - Recibe notificación visual en Dashboard.
    - Revisa detalles del pedido.
    - Cambia estado a `confirmado` o `completado`.
    - Puede contactar al cliente vía link de WhatsApp generado.

3.  **Flujo de Administración**:
    - ABM (Alta, Baja, Modificación) de Productos (incluye subida de imágenes).
    - Gestión de usuarios (crear nuevos trabajadores).
    - Reportes globales.

## 7. Guía de Interfaz (UI)

- **Layouts**:
    - `layouts/public.blade.php`: Para la tienda y páginas públicas. Usa diseño moderno con Tailwind.
    - `layouts/app.blade.php`: Para usuarios autenticados (Dashboards). Generalmente integra AdminLTE o estructura de sidebar.
    - `layouts/guest.blade.php`: Para login/registro.

## 8. Comandos Útiles

- **Iniciar Servidor Local**: `php artisan serve`
- **Compilar Assets (Dev)**: `npm run dev`
- **Compilar Assets (Prod)**: `npm run build`
- **Limpiar Caché**: `php artisan optimize:clear`
- **Migrar BD**: `php artisan migrate`

---
*Este documento debe ser actualizado cada vez que se realicen cambios estructurales, de base de datos o de lógica de negocio importante.*
