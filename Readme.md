# Sistema de Alquiler de Vehículos

Aplicación web full-stack desarrollada en **Laravel (PHP)** para la gestión completa de un servicio de alquiler de vehículos: inventario, disponibilidad por fechas, reservas, extras y facturación.

## Descripción

El sistema resuelve el problema de las reservas superpuestas y facilita todo el proceso de rentar un vehículo, desde la búsqueda por fechas y capacidad hasta la confirmación del pago y la entrega del vehículo, incluyendo servicios adicionales y una lista de favoritos por usuario.

## Tecnologías

- **Backend:** Laravel (PHP), gestionado con Composer
- **Base de datos:** PostgreSQL, estructurada mediante migraciones
- **ORM:** Eloquent (relaciones `hasMany`, `belongsTo`, `belongsToMany`)
- **Vistas:** Blade con layouts y componentes reutilizables
- **Estilos:** Tailwind CSS o Bootstrap
- **Autenticación:** Laravel Breeze

## Funcionalidades principales

- Registro, login y recuperación de contraseña.
- Catálogo de vehículos con búsqueda opcional por fechas y cantidad de pasajeros.
- Ficha detallada de cada vehículo (categoría, transmisión, combustible, capacidad, prestaciones).
- Lista de favoritos por usuario.
- Reserva con selección de servicios extras (GPS, silla infantil, asistencia en carretera, seguros, portaequipajes, etc.).
- Cálculo automático del costo total (días de alquiler + extras).
- Validación de disponibilidad para evitar choques de reservas.
- Confirmación de pago con entrega de placa, kilometraje inicial y nivel de combustible/carga.
- Panel de administración protegido por middleware: gestión de vehículos, categorías, extras, reservas y usuarios.
- Dashboard con métricas reales (reservas activas, vehículos disponibles, ganancias).

## Roles del sistema

| Rol | Permisos |
|---|---|
| Cliente | Busca vehículos, reserva, gestiona sus favoritos y consulta sus reservas |
| Administrador | Gestiona inventario, categorías, extras, reservas y usuarios |

## Requisitos previos

- PHP >= 8.1
- Composer
- PostgreSQL
- Node.js y npm (para compilar los assets del frontend)

## Instalación

```bash
# Clonar el repositorio
git clone <url-del-repositorio>
cd sistema-alquiler-vehiculos

# Instalar dependencias de PHP
composer install

# Copiar el archivo de entorno y generar la clave de la aplicación
cp .env.example .env
php artisan key:generate

# Configurar la conexión a la base de datos en el archivo .env
# DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.

# Ejecutar las migraciones
php artisan migrate

# Instalar dependencias de frontend y compilar assets
npm install
npm run dev

# Levantar el servidor de desarrollo
php artisan serve
```

La aplicación quedará disponible en `http://localhost:8000`.

## Estructura de la base de datos

El sistema se compone de las siguientes entidades principales: `User`, `Category`, `Vehicle`, `Reservation`, `Extra`, `Favorite` y las tablas pivote `reservation_extra` y `favorites`. El detalle completo de campos y relaciones está documentado en [`documentacion.md`](./documentacion.md).

## Documentación del proyecto

| Archivo | Contenido |
|---|---|
| [`documentacion.md`](./documentacion.md) | Documentación técnica completa: requerimientos funcionales, modelo de datos, relaciones y listado de vistas/formularios/rutas |
| [`pasos.md`](./pasos.md) | Guía paso a paso para construir el proyecto en el orden recomendado |

## Orden de desarrollo recomendado

1. Configuración inicial del proyecto y autenticación.
2. Migraciones de la base de datos.
3. Modelos Eloquent y relaciones.
4. Roles y middleware.
5. CRUD del administrador (categorías, vehículos, extras).
6. Búsqueda, disponibilidad y capacidad.
7. Flujo de reserva completa (extras, pago, entrega, favoritos).
8. Dashboard, panel avanzado de administración y pulido final.

El detalle completo de cada paso está en [`pasos.md`](./pasos.md).