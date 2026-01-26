# 🎨 Guía de Estilos - Distribuidora

## Paleta de Colores

### Colores Principales

| Color | HEX | Uso Sugerido |
|-------|-----|-------------|
| **Rojo Carmesí Vivo** | `#D9232E` | Botones de compra, alertas, ofertas especiales. El color de la acción. |
| **Dorado Amarillo** | `#FFD700` | Detalles llamativos, insignias de "Nuevo", hover en enlaces. |
| **Azul Marino Profundo** | `#0A192F` | Un excelente color de contraste. Úsalo para barras de navegación o secciones de "Acerca de". El azul profundo complementa maravillosamente al dorado. |
| **Blanco Puro** | `#FFFFFF` | Fondo principal para las secciones de contenido largo y catálogo para máxima legibilidad. |
| **Gris Claro** | `#F4F4F4` | Fondos alternos para separar secciones en la página de inicio. |

## Variables CSS

```css
:root {
    --rojo-carmesi: #D9232E;
    --rojo-carmesi-hover: #B31D26;
    --dorado: #FFD700;
    --dorado-hover: #E6C200;
    --azul-marino: #0A192F;
    --blanco-puro: #FFFFFF;
    --gris-claro: #F4F4F4;
}
```

## Componentes Personalizados

### Botones

```html
<!-- Botón Carmesí -->
<button class="btn btn-carmesi">Comprar Ahora</button>

<!-- Botón Dorado -->
<button class="btn btn-dorado">Ver Detalles</button>

<!-- Botón Outline -->
<button class="btn btn-outline-carmesi">Cancelar</button>
```

### Cards

```html
<!-- Card Estándar -->
<div class="card">
    <div class="card-header">Título</div>
    <div class="card-body">Contenido</div>
</div>

<!-- Card Distribuidora (con bordes dorados) -->
<div class="card card-distribuidora">
    <div class="card-header">Producto Destacado</div>
    <div class="card-body">Contenido</div>
</div>
```

### Badges

```html
<span class="badge badge-carmesi">Nuevo</span>
<span class="badge badge-dorado">Destacado</span>
<span class="stock-badge">En Stock</span>
```

## Clases Utilitarias

```html
<!-- Textos -->
<p class="text-carmesi">Texto en rojo carmesí</p>
<p class="text-dorado">Texto en dorado</p>

<!-- Fondos -->
<div class="bg-carmesi">Fondo carmesí</div>
<div class="bg-dorado">Fondo dorado</div>

<!-- Bordes -->
<div class="border-carmesi">Borde carmesí</div>
<div class="border-dorado">Borde dorado</div>
```

## Animaciones

```html
<!-- Fade In Up -->
<div class="fade-in-up">Contenido animado</div>

<!-- Pulse Dorado -->
<button class="btn btn-dorado pulse-dorado">¡Oferta!</button>
```

## Aplicación en Diferentes Vistas

### Navbar
- Gradiente carmesí de fondo
- Texto dorado para el brand
- Hover dorado en links

### Cards de Productos
- Borde dorado en hover
- Precio en rojo carmesí
- Badge de stock en dorado

### Tablas AdminLTE
- Header azul marino
- Hover con tinte carmesí
- Stripes con tinte dorado

### Formularios
- Focus dorado en inputs
- Botones submit en carmesí
- Labels en azul marino

## Archivo CSS Principal

Ubicación: `/public/css/distribuidora-style.css`

Este archivo contiene todos los estilos personalizados del proyecto y está incluido en:
- Layout público (`layouts/public.blade.php`)
- AdminLTE (mediante `vendor/adminlte/partials/common/custom-css.blade.php`)

## Integración con AdminLTE

Configuración en `config/adminlte.php`:
- `classes_sidebar`: 'sidebar-dark-danger'
- `classes_topnav`: 'navbar-danger'
- `classes_brand`: 'bg-dark'
- `classes_brand_text`: 'text-warning'

---

**Nota**: Todos los colores están diseñados para mantener una identidad de marca coherente y profesional en todo el proyecto.
