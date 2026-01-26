# 🧠 AI Context & Memory File

Este archivo existe para dar contexto inmediato a cualquier agente de IA sobre el estado, reglas y arquitectura del proyecto. **Debe ser leído al inicio de cada sesión.**

## 1. Identidad del Proyecto
- **Nombre**: Distribuidora
- **Objetivo**: Sistema de gestión de pedidos y ventas para clientes, trabajadores y administradores.
- **Estado Actual**: Fase de refinamiento y pruebas (Beta).

## 2. Stack Tecnológico (Estricto)
- **Backend**: Laravel 10 (PHP 8.1+), MySQL.
- **Frontend Público**: Blade + Tailwind CSS (Diseño personalizado, sin AdminLTE).
- **Frontend Admin/Trabajador**: AdminLTE 3 (Blade Components).
- **JS**: Alpine.js para interactividad ligera. Evitar React/Vue salvo necesidad extrema.

## 3. Arquitectura y Reglas Clave
- **Roles**: 
    - `Cliente`: Acceso solo a catálogo y carrito.
    - `Trabajador`: Acceso a gestión de pedidos (AdminLTE).
    - `Administrador`: Acceso total (CRUD Productos/Usuarios).
- **Imágenes**: Se almacenan en `public/storage/products`.
- **Rutas**: Separadas por middleware de roles en `web.php`.
- **Inventario**: El stock se descuenta SOLO al marcar el pedido como `completado`. Se restaura si pasa de `completado` a `cancelado`.

## 4. Reglas de Comportamiento para el Agente
1.  **Actualización de Docs**: SIEMPRE actualizar `TASKS.md`, `ARCHITECTURE.md` o este archivo tras cambios estructurales.
2.  **Continuidad**: Antes de sugerir refactorizaciones, verificar si rompen flujos existentes (especialmente el checkout).
3.  **Estilo**: Mantener clases de utilidad de Tailwind en el frontend público; no mezclar con Bootstrap.

## 5. Estado Actual (Última Sesión)
- **Último Logro**: Implementación completa de subida de imágenes y visualización en catálogo.
- **Tarea Activa**: Verificación de flujos de venta.
- **Archivo de Tareas Vivo**: Ver `./TASKS.md` para el checklist detallado.