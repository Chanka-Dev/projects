# Portafolio de Proyectos

Este repositorio contiene copias de varios proyectos desarrollados con diferentes stacks tecnológicos para propósitos académicos, personales y municipales.

> **⚠️ Nota Importante:** Este repositorio es únicamente para control de versiones en GitHub. Los proyectos en producción se encuentran en `/var/www/` y se sincronizan aquí para hacer push.

## 🎯 Proyectos Destacados

- 🏛️ **FixSucre:** Sistema de reportes ciudadanos (Laravel + PostgreSQL)
- 💰 **Finanzas:** Gestión financiera (Node.js + React/Vue)
- 🦕 **Colorsaurio:** Teoría del color educativa con dinosaurios
- 🛒 **Distribuidora:** Sistema de ventas e inventario
- 💼 **Mi-Proyecto:** Gestión de citas y servicios
- 📊 **SuperOrganico:** Sistema contable con cálculos fiscales Bolivia
- 📖 **Roles-Permisos:** Sistema de roles y permisos con recetario (Laravel + Tailwind)

## 📁 Estructura del Sistema

```
/var/www/
├── distribuidora/          # ✅ Carpeta de trabajo en producción
├── mi-proyecto/            # ✅ Carpeta de trabajo en producción
├── superorganico/          # ✅ Carpeta de trabajo en producción
├── FixSucre/               # ✅ Carpeta de trabajo en producción
├── finanzas/               # ✅ Carpeta de trabajo en producción
├── colores/                # ✅ Carpeta de trabajo en producción
└── proyectos/              # 📦 Repositorio Git (este)
    ├── distribuidora-project/  # Copia para GitHub
    ├── mi-proyecto/            # Copia para GitHub
    ├── superorganico/          # Copia para GitHub
    ├── FixSucre/               # Copia para GitHub
    ├── finanzas/               # Copia para GitHub
    ├── colores/                # Copia para GitHub
    └── roles-permisos/         # Copia para GitHub
```

## 🔄 Workflow de Actualización

Para sincronizar cambios al repositorio de GitHub:

1. **Trabajar** en las carpetas de producción en `/var/www/[proyecto]`
2. **Copiar** cambios al repositorio:
   ```bash
   cd /var/www
   
   # Distribuidora
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./distribuidora/ ./proyectos/distribuidora-project/
   
   # Mi-Proyecto
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./mi-proyecto/ ./proyectos/mi-proyecto/
   
   # SuperOrganico
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./superorganico/ ./proyectos/superorganico/
   
   # FixSucre
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./FixSucre/ ./proyectos/FixSucre/
   
   # Finanzas
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' ./finanzas/ ./proyectos/finanzas/
   
   # Colores (Colorsaurio)
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./colores/ ./proyectos/colores/

   # Roles-Permisos
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./roles-permisos/ ./proyectos/roles-permisos/
   ```
   
3. **Commit y Push** desde el repositorio:
   ```bash
   cd /var/www/proyectos
   git add .
   git commit -m "Actualizar proyectos [fecha/descripción]"
   git push origin main
   ```

---

## 🚀 Proyectos Incluidos

### 1. 🛒 Distribuidora Project
Sistema de gestión para distribuidora con control de inventario, pedidos y ventas.

**Características:**
- Gestión de productos y categorías
- Control de pedidos con estados
- Gestión de clientes
- Sistema de roles (Cliente, Trabajador, Administrador)
- Integración con WhatsApp
- Modo oscuro
- Dashboard con estadísticas

**Stack Tecnológico:**
- Laravel 10.49.1
- PHP 8.1+
- MySQL
- Tailwind CSS
- Alpine.js
- AdminLTE 3

**URL Producción:** `http://181.188.171.38/distribuidora`

---

### 2. 💼 Mi Proyecto
Sistema de gestión de citas y servicios con control de comisiones para trabajadoras.

**Características:**
- Gestión de citas y servicios
- Control de clientes y trabajadoras
- Sistema de pagos y comisiones
- Historial de servicios
- Recordatorios automáticos
- Generación de pagos semanales

**Stack Tecnológico:**
- Laravel 10.x
- PHP 8.1+
- MySQL
- AdminLTE 3

**Módulos:**
- Clientes
- Trabajadoras
- Servicios
- Citas
- Pagos
- Historial de Servicios
- Recordatorios

---

### 3. 📊 SuperOrganico
Sistema contable completo con integración de cálculos de impuestos para Bolivia.

**Características:**
- Plan de cuentas contable completo
- Gestión de compras y ventas
- Control de inventario con sistema de lotes
- **Cálculo automático de IVA e IT** según normativa boliviana
- Reportes contables: Balance, Estado de Resultados, Libro Diario, Libro Mayor
- Gastos operativos por categorías
- Gestión de proveedores y clientes

**Documentación Especial:**
- 📄 [Cálculo de Impuestos Bolivia](superorganico/CALCULO_IMPUESTOS_BOLIVIA.md)

**Stack Tecnológico:**
- Laravel 10.x
- PHP 8.1+
- MySQL
- AdminLTE 3

**Módulos:**
- Plan de Cuentas
- Asientos Contables
- Compras y Ventas
- Inventario
- Reportes Fiscales (IVA, IT)
- Gastos Operativos

---

### 4. 🏛️ FixSucre
Sistema de reportes ciudadanos para la gestión municipal de problemas urbanos.

**Características:**
- Reportes ciudadanos con geolocalización
- Sistema de asignación a gestores municipales
- Seguimiento de reportes por áreas
- Panel de administración para gestores
- Autenticación de usuarios (Ciudadano/Gestor)
- Categorías de problemas urbanos
- Sistema de seguimiento y resolución
- Interfaz responsive

**Stack Tecnológico:**
- Laravel 10
- PHP 8.1.2
- PostgreSQL 14.22
- Tailwind CSS
- Composer 2.8.12

**Módulos:**
- Usuarios y Roles
- Ciudadanos
- Gestores Municipales
- Áreas Municipales
- Categorías de Problemas
- Reportes
- Asignaciones
- Seguimientos

**URL Producción:** `http://181.188.171.38/FixSucre`

**Historial de Desarrollo:**
- 18/03/2026: Configuración inicial con Filament 3.3
- 25/03/2026: Migración a Laravel puro con Tailwind
- 01/04/2026: Corrección autenticación y logout
- 08/04/2026: Mejoras responsive y reasignación de reportes

---

### 5. 💰 Finanzas
Sistema de gestión financiera con arquitectura frontend/backend separada.

**Características:**
- Arquitectura desacoplada (Backend API + Frontend SPA)
- Gestión de ingresos y egresos
- Categorización de transacciones
- Dashboard con métricas financieras
- Reportes y estadísticas

**Stack Tecnológico:**
- **Backend:** Node.js + Express
- **Frontend:** React/Vue (SPA)
- **Base de Datos:** MySQL/PostgreSQL
- **API RESTful**

**Estructura:**
```
finanzas/
├── backend/     # API Node.js
├── frontend/    # Aplicación SPA
└── database/    # Scripts SQL
```

---

### 6. 🦕 Colores (Colorsaurio)
Plataforma educativa de teoría del color con temática de dinosaurios.

**Características:**
- Teoría del color con análisis psicológico
- Generador de paletas temáticas (6 tipos)
- Códigos HEX y RGB copiables
- Vista previa de colores en diseño web
- Temática divertida con dinosaurios 🦖
- Interfaz completamente responsive

**Stack Tecnológico:**
- Laravel 10.50.0
- PHP 8.1.2
- Tailwind CSS (vía CDN)
- Alpine.js
- Sin base de datos (paletas hardcodeadas)

**Tipos de Paletas:**
- Urgencia y Acción (Rojos)
- Confianza y Profesionalismo (Azules)
- Optimismo y Energía (Amarillos)
- Naturaleza y Crecimiento (Verdes)
- Creatividad y Lujo (Púrpuras)
- Energía y Diversión (Naranjas)

**URL Producción:** `http://localhost/colores`

**Características Especiales:**
- RAAAWWWRRRR! 🦕
- Footer épico: "Teoría del Color Mesosoica"

---

## 💻 Instalación (Para Desarrollo Local)

Cada proyecto tiene sus propias dependencias. Para instalar cualquiera de ellos:

```bash
# 1. Clonar el repositorio
git clone https://github.com/Chanka-Dev/projects.git
cd projects

# 2. Entrar al proyecto deseado
cd distribuidora-project  # o mi-proyecto, o superorganico

# 3. Instalar dependencias de PHP
composer install

# 4. Instalar dependencias de Node (si aplica)
npm install

# 5. Configurar el archivo .env
cp .env.example .env
php artisan key:generate

# 6. Configurar la base de datos en .env
# DB_DATABASE=nombre_bd
# DB_USERNAME=usuario
# DB_PASSWORD=contraseña

# 7. Ejecutar migraciones
php artisan migrate --seed

# 8. Compilar assets (si aplica)
npm run dev  # o npm run build para producción

# 9. Iniciar servidor de desarrollo
php artisan serve
```

---

## 📈 Estadísticas

- **Total de proyectos:** 6
- **Lenguajes principales:** PHP, JavaScript, SQL
- **Frameworks Backend:** Laravel 10, Node.js + Express
- **Frameworks Frontend:** Tailwind CSS, Alpine.js, React/Vue
- **Bases de datos:** MySQL, PostgreSQL
- **Servidor:** Nginx + PHP 8.1-FPM
- **Temáticas:** Contabilidad, Gestión Municipal, Finanzas, E-commerce, Servicios, Educación

---

## 🛠️ Stack Tecnológico Completo

### Backend
- **PHP:** 8.1.2
- **Laravel:** 10.x
- **Node.js:** Express API
- **Composer:** 2.8.12

### Frontend
- **Tailwind CSS**
- **Alpine.js**
- **React/Vue (SPA)**
- **AdminLTE 3**

### Bases de Datos
- **MySQL**
- **PostgreSQL 14.22**

### Servidor & Deployment
- **Nginx**
- **PHP-FPM 8.1**
- **Producción:** http://181.188.171.38

---

## 👨‍💻 Autor

**Chanka-Dev**
- GitHub: [@Chanka-Dev](https://github.com/Chanka-Dev)
- Portafolio: Este repositorio

---

## 📄 Licencia

Proyectos de uso académico y personal. Ver el README de cada proyecto para más detalles.
