# Colorsaurio - Teoría del Color 🎨

Aplicación web desarrollada en Laravel 10 enfocada en la psicología del color y su aplicación en diseño web.

## 📋 Descripción del Proyecto

Colorsaurio 🦕 es una plataforma educativa con temática de dinosaurios que combina teoría del color con herramientas prácticas para diseñadores y desarrolladores web. El proyecto explora cómo los colores primarios, secundarios y sus combinaciones afectan la percepción psicológica y el comportamiento del usuario. **RAAAWWWRRRR!**

## 🎯 Características

### 1. Sección de Teoría 📚
- **Colores Primarios**: Rojo, Azul y Amarillo con notación HEX y RGB
- **Impacto Psicológico**: Análisis detallado de cada color
- **Aplicaciones Prácticas**: Cuándo y cómo usar cada color
- **Referencias**: Links a fuentes académicas y profesionales

### 2. Generador de Paletas 🎨
6 tipos de paletas prediseñadas:
- **Urgencia y Acción** (Rojos): Para CTAs y promociones
- **Confianza y Profesionalismo** (Azules): Para tech y finanzas
- **Optimismo y Energía** (Amarillos): Para marcas juveniles
- **Naturaleza y Crecimiento** (Verdes): Para productos ecológicos
- **Creatividad y Lujo** (Púrpuras): Para marcas premium
- **Energía y Diversión** (Naranjas): Para deportes y entretenimiento

### 3. Funcionalidades
- ✅ Visualización de paletas con 5 colores cada una
- ✅ Códigos HEX y RGB copiables con un clic
- ✅ Vista previa de colores en contexto de diseño web
- ✅ Diseño responsive con Tailwind CSS
- ✅ Navegación intuitiva

## 🛠️ Tecnologías Utilizadas

- **Framework**: Laravel 10.50.0
- **PHP**: 8.1.2
- **CSS**: Tailwind CSS (vía CDN)
- **Servidor Web**: Nginx
- **Base de Datos**: Ninguna (paletas hardcodeadas)

## 📂 Estructura del Proyecto

```
colores/
├── app/
│   └── Http/
│       └── Controllers/
│           └── PaletaController.php    # Lógica de paletas
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Layout principal
│       ├── generador/
│       │   ├── index.blade.php         # Selector de paletas
│       │   └── show.blade.php          # Visualización de paleta
│       ├── home.blade.php              # Página de inicio
│       └── teoria.blade.php            # Teoría del color
└── routes/
    └── web.php                         # Rutas del proyecto
```

## 🚀 Rutas Disponibles

| Ruta | Descripción |
|------|-------------|
| `/` | Página de inicio |
| `/teoria` | Sección de teoría del color |
| `/generador` | Selector de tipos de paletas |
| `/generador/{tipo}` | Vista de paleta específica |

## 🌐 Acceso

- **Local**: http://localhost/colores
- **Red Local**: http://192.168.0.10/colores
- **IP Pública**: http://181.188.171.38/colores

## 💡 Uso

1. **Explora la Teoría**: Aprende sobre la psicología de colores primarios
2. **Genera Paletas**: Selecciona según tu objetivo (urgencia, confianza, etc.)
3. **Copia Códigos**: Usa los códigos HEX/RGB en tus proyectos
4. **Visualiza**: Ve cómo lucen los colores en contexto real

## 📚 Fundamentos Teóricos

El proyecto se basa en investigación sobre:
- Percepción psicológica de colores
- Teoría del círculo cromático
- Combinaciones cromáticas (complementarios, análogos, tríadas)
- Aplicación en diseño web y marketing
- Casos de uso en diferentes industrias

## 👨‍💻 Desarrollo

Proyecto desarrollado como práctica de Laravel, cumpliendo con:
- Configuración de rutas en `web.php`
- Uso de controladores y vistas
- Implementación de Blade templates
- Diseño responsive con Tailwind CSS
- Sin uso de base de datos (enfoque en lógica de presentación)

## 📝 Notas

- Tiempo estimado de desarrollo: 3 horas
- Enfoque: MVP funcional con diseño atractivo
- Sin autenticación ni base de datos
- Paletas predefinidas basadas en investigación real

## 🔗 Referencias

- [Parachute Design - Color Theory](https://parachutedesign.ca/blog/consumer-behaviour-colour-theory/)
- [Verywell Mind - Color Psychology](https://www.verywellmind.com/)
- [Psicología y Mente - Significado de Colores](https://psicologiaymente.com/)
- [Smashing Magazine - Color Theory](https://www.smashingmagazine.com/)

---

**Fecha de creación**: 28 de enero de 2026  
**Framework**: Laravel 10  
**Temática**: 🦕 Dinosaurios y Teoría del Color Mesosoica  
**Autor**: Proyecto Académico  
**Rugido oficial**: RAAAWWWRRRR
