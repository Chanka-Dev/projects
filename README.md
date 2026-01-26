# Portafolio de Proyectos

Este repositorio contiene copias de varios proyectos Laravel desarrollados para diferentes propósitos académicos y personales.

> **⚠️ Nota Importante:** Este repositorio es únicamente para control de versiones en GitHub. Los proyectos en producción se encuentran en `/var/www/` y se sincronizan aquí para hacer push.

## 📁 Estructura del Sistema

```
/var/www/
├── distribuidora/          # ✅ Carpeta de trabajo en producción
├── mi-proyecto/            # ✅ Carpeta de trabajo en producción
├── superorganico/          # ✅ Carpeta de trabajo en producción
└── proyectos/              # 📦 Repositorio Git (este)
    ├── distribuidora-project/  # Copia para GitHub
    ├── mi-proyecto/            # Copia para GitHub
    └── superorganico/          # Copia para GitHub
```

## 🔄 Workflow de Actualización

Para sincronizar cambios al repositorio de GitHub:

1. **Trabajar** en las carpetas de producción: `/var/www/distribuidora`, `/var/www/mi-proyecto`, `/var/www/superorganico`
2. **Copiar** cambios al repositorio:
   ```bash
   cd /var/www
   rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.env' --exclude='storage/logs/*' --exclude='storage/framework/sessions/*' --exclude='storage/framework/views/*' --exclude='storage/framework/cache/*' ./distribuidora/ ./proyectos/distribuidora-project/
   ```
3. **Commit y Push** desde el repositorio:
   ```bash
   cd /var/www/proyectos
   git add .
   git commit -m "Actualizar cambios de [proyecto]"
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

- **Total de proyectos:** 3
- **Lenguajes principales:** PHP, JavaScript
- **Frameworks:** Laravel 10, Tailwind CSS, Alpine.js
- **Base de datos:** MySQL
- **Servidor:** Nginx + PHP 8.1-FPM

---

## 👨‍💻 Autor

**Chanka-Dev**
- GitHub: [@Chanka-Dev](https://github.com/Chanka-Dev)
- Portafolio: Este repositorio

---

## 📄 Licencia

Proyectos de uso académico y personal. Ver el README de cada proyecto para más detalles.
