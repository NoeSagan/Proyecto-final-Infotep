# AutoAlquiler

Aplicación web full-stack para la gestión completa de un servicio de alquiler de vehículos. Desarrollada como proyecto final del curso de Desarrollo Web con PHP en **INFOTEP**.

---

## Descripción

AutoAlquiler digitaliza el proceso completo de una empresa de alquiler de vehículos: el cliente busca por fechas, capacidad y categoría, reserva el vehículo, selecciona extras y confirma el pago. El administrador gestiona el inventario, atiende las reservas y consulta reportes en tiempo real. El sistema previene choques de reserva, aplica cargos por cancelación tardía y mantiene un historial completo de entregas.

---

## Problema que resuelve

Las empresas pequeñas de alquiler de vehículos gestionan sus reservas de forma manual (llamadas, hojas de cálculo, WhatsApp), lo que genera choques de disponibilidad, pérdida de información y dificultad para llevar el control de ingresos y estado de la flota.

AutoAlquiler centraliza todo el proceso en una sola plataforma web: el cliente reserva en línea con validación de disponibilidad en tiempo real, y el administrador tiene visibilidad completa del inventario, las reservas y los ingresos desde un panel de control.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.3+ · Laravel 13 |
| Autenticación | Laravel Breeze |
| Base de datos | PostgreSQL (Supabase) |
| ORM | Eloquent |
| Vistas | Blade |
| Estilos | Tailwind CSS 3.4 |
| Bundler | Vite 6 |

---

## APIs externas

| API | Uso |
|---|---|
| **Auto.dev** | Lookup de VIN para autocompletar ficha técnica al crear un vehículo |
| **Car Specs** (RapidAPI) | Catálogo de 1 000+ modelos pre-cacheados (fuente principal del catálogo) |
| **AutoScout24** (RapidAPI) | Listings reales con imágenes (fuente de máxima prioridad del catálogo) |
| **Wikimedia Commons** | Fotografías de vehículos por marca y modelo (caché 7 días) |

### Catálogo - tres fuentes en cascada

1. **AutoScout24** - listings reales con imágenes (API de pago, prioridad máxima).
2. **Car Specs** - 1 000+ modelos pre-cacheados, pre-generar con `php artisan catalog:build`.
3. **Base de datos local** - 200 vehículos sembrados como fallback.

---

## Funcionalidades

### Cliente
- Registro, login y recuperación de contraseña.
- Catálogo de vehículos con filtros por fecha, pasajeros, categoría, combustible, transmisión y presupuesto.
- Ficha técnica detallada por vehículo (categoría, transmisión, combustible, capacidades, kilometraje, extras).
- Fotografías automáticas de cada vehículo vía Wikimedia Commons.
- Lista de favoritos personal (toggle instantáneo).
- Flujo de reserva en tres pasos: fechas + extras → resumen de pago → comprobante.
- Cálculo del costo total en tiempo real (días × tarifa + extras × días).
- Validación de disponibilidad: no se puede reservar un vehículo ya ocupado en las fechas solicitadas.
- Cancelación de reservas con política de cargos según antelación.
- Comprobante imprimible de la reserva confirmada.

### Administrador
- Dashboard con métricas en tiempo real: reservas activas, vehículos disponibles, ingresos del mes, total de clientes.
- CRUD completo de vehículos, categorías y extras.
- Lookup de VIN para autocompletar la ficha técnica desde la API de Auto.dev.
- Registro de mantenimiento por vehículo.
- Gestión de reservas: visualización, cambio de estado y exportación CSV.
- Gestión de usuarios: listado, detalle y cambio de rol.
- Reportes: vehículos más alquilados, ingresos por mes, ocupación por categoría.

---

## Roles

| Rol | Acceso |
|---|---|
| `cliente` | Catálogo, reservas, favoritos, perfil |
| `admin` | Todo lo anterior + panel de administración completo |

La redirección tras el login es directa: el admin va siempre al dashboard, el cliente al catálogo.

---

## Política de cancelación

| Antelación a la fecha de inicio | Cargo |
|---|---|
| > 48 horas (o reserva pendiente) | Gratuita |
| 24 – 48 horas | 25 % del total |
| < 24 horas | 50 % del total |

---

## Requisitos previos

- PHP 8.3+ con la extensión `pdo_pgsql` habilitada
- Composer
- Node.js 18+ y npm

> **Windows con Laravel Herd Lite:** Herd Lite instala PHP sin la extensión `pgsql`. Usa un intérprete separado que la incluya (ej. `C:\php`). En ese caso reemplaza todos los comandos `php artisan` por `C:\php\php.exe artisan`.

---

## Instalación y puesta en marcha

> El archivo `.env` con las credenciales de la base de datos y las API keys se entrega por separado. Colócalo en la raíz del proyecto antes de continuar.

### 1. Clonar el repositorio

```bash
git clone https://github.com/NoeSagan/Proyecto-final-Infotep.git
cd Proyecto-final-Infotep
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 4. Instalar dependencias frontend y compilar

```bash
npm install
npm run build
```

### 5. Crear las tablas y cargar los datos de prueba

```bash
php artisan migrate --seed
```

Esto crea todas las tablas en la base de datos y carga:
- 4 usuarios (1 admin + 3 clientes)
- 6 categorías y 7 extras
- 200 vehículos distribuidos en las 6 categorías
- Reservas de prueba en distintos estados y fechas

Si necesitas reiniciar la base de datos desde cero:

```bash
php artisan migrate:fresh --seed
```

### 6. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación queda disponible en `http://localhost:8000`.

---

## Catálogo de vehículos - tres fuentes en cascada

El catálogo funciona sin API keys gracias a los 200 vehículos sembrados. Si quieres ampliar el catálogo con datos externos, hay dos opciones adicionales:

### Opción A - Car Specs (RapidAPI)

Pre-cachea más de 1 000 modelos adicionales en la base de datos local. Requiere `CAR_SPECS_API_KEY` en el `.env`.

```bash
php artisan catalog:build
```

Una vez ejecutado, los modelos aparecen en el catálogo automáticamente. El caché dura 24 horas.

### Opción B - AutoScout24 (RapidAPI)

Muestra listings reales con imágenes y precios de mercado. Requiere `AUTOSCOUT24_API_KEY` en el `.env`.

No requiere ningún comando previo. Los vehículos de esta fuente se importan automáticamente a la base de datos la primera vez que un usuario abre su ficha de detalle (import-on-demand). A partir de ese momento quedan disponibles para reserva como cualquier otro vehículo.

> Si las API keys no están configuradas o la cuota está agotada, el sistema cae automáticamente al catálogo local sin mostrar errores.

---

## Credenciales de prueba

| Rol | Email | Contraseña |
|---|---|---|
| Admin | admin@alquiler.com | password |
| Cliente | maria@alquiler.com | password |
| Cliente | carlos@alquiler.com | password |
| Cliente | laura@alquiler.com | password |

---

## Documentación

| Archivo | Contenido |
|---|---|
| [`Documentacion/sistema.md`](./Documentacion/sistema.md) | Arquitectura, base de datos, módulos, rutas, seguridad y decisiones técnicas |
| [`Documentacion/diseno-ui.md`](./Documentacion/diseno-ui.md) | Sistema de diseño: componentes, paleta de color, radios, efectos y justificación de uso |
