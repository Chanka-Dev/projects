# Sistema de Distribuidora

Sistema web para gestión de distribuidora con roles de Cliente, Trabajador y Administrador.

## 🚀 Inicio Rápido

### Opción 1: Automática (Recomendada)
Ejecuta el script de inicio que instalará dependencias, configurará el entorno y lanzará el servidor:

```bash
./start.sh
```

### Opción 2: Manual

1.  Instalar dependencias:
    ```bash
    composer install
    npm install
    ```
2.  Configurar entorno:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
3.  Configurar base de datos en `.env` y migrar:
    ```bash
    php artisan migrate --seed
    ```
4.  Iniciar servidor:
    ```bash
    php artisan serve
    ```

Accede al sistema en: **http://localhost:8000**

## 🔑 Credenciales de Acceso

| Rol | Email | Password | Acceso |
|-----|-------|----------|--------|
| **Administrador** | `admin@distribuidora.com` | `admin123` | Dashboard Completo |
| **Trabajador** | `trabajador@distribuidora.com` | `trabajador123` | Gestión de Pedidos |
| **Cliente** | `cliente@distribuidora.com` | `cliente123` | Catálogo y Pedidos |

## 📖 Documentación

Este repositorio contiene documentación detallada para diferentes propósitos:

-   [**ARCHITECTURE.md**](./ARCHITECTURE.md): **Memoria Técnica**. Explica la arquitectura, base de datos, estructura de directorios y tecnologías usadas. Ideal para desarrolladores.
-   [**GUIA_USO.md**](./GUIA_USO.md): **Manual de Usuario**. Guía paso a paso sobre cómo usar el sistema para cada rol (Cliente, Trabajador, Admin).
-   [**GUIA-ESTILOS.md**](./GUIA-ESTILOS.md): **Frontend & UI**. Paleta de colores, componentes y guías de diseño.

## ✨ Características Principales

### Roles y Permisos
-   **Cliente**: Auto-registro, carrito de compras, historial de pedidos.
-   **Trabajador**: Gestión de estados de pedidos (Pendiente -> Confirmado -> Completado).
-   **Administrador**: Gestión total (Usuarios, Productos, Clientes), reportes.

### Flujos de Trabajo
-   **Compras**: Catálogo público -> Carrito -> Checkout.
-   **Gestión**: Dashboard administrativo con estadísticas y controles CRUD.

### Stack Tecnológico
-   **Backend**: Laravel 10
-   **Frontend**: Blade + AdminLTE 3 + Bootstrap 5
-   **DB**: MySQL

## 🛠 Comandos Útiles

-   Limpiar caché: `php artisan optimize:clear`
-   Verificar rutas: `php artisan route:list`
-   Recargar base de datos (reset): `php artisan migrate:fresh --seed`