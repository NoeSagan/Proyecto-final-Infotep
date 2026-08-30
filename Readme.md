# AutoAlquiler

Aplicación web full-stack para la gestión completa de un servicio de alquiler de vehículos. Desarrollada como proyecto final del curso de Desarrollo Web con PHP en **INFOTEP**.

---

## Descripción

AutoAlquiler digitaliza el proceso completo de una empresa de alquiler de vehículos: el cliente busca por fechas, capacidad y categoría, reserva el vehículo, selecciona extras y confirma el pago. El administrador gestiona el inventario, atiende las reservas y consulta reportes en tiempo real. El sistema previene choques de reserva, aplica cargos por cancelación tardía y mantiene un historial completo de entregas.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.1+ · Laravel 11 |
| Autenticación | Laravel Breeze |
| Base de datos | PostgreSQL (Supabase) |
| ORM | Eloquent |
| Vistas | Blade · ddfsn/blade-components 1.6.1 |
| Estilos | Tailwind CSS 3.4 |
| JavaScript | Alpine.js 3 |
| Bundler | Vite 8 |

---

## Funcionalidades

### Cliente
- Registro, login y recuperación de contraseña.
- Catálogo de vehículos con filtros por fecha, pasajeros, categoría, combustible, transmisión y presupuesto.
- Ficha técnica detallada por vehículo (categoría, transmisión, combustible, capacidades, kilometraje, extras).
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

### Catálogo (tres fuentes en cascada)
1. **AutoScout24** - listings reales con imágenes (API de pago, prioridad máxima).
2. **CarSpecs** - 1 000+ modelos pre-cacheados (caché 24 h, fuente principal).
3. **Base de datos local** - vehículos sembrados e importados manualmente.

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

- PHP 8.1+ con extensión `pgsql` habilitada
- Composer
- Node.js y npm
- Acceso a una base de datos PostgreSQL

> **Nota para Windows con Herd Lite:** el PHP de Herd Lite no incluye la extensión `pgsql`. Usar el intérprete en `C:\php\php.exe` que sí la tiene compilada.

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd autoalquiler

# 2. Dependencias PHP
composer install

# 3. Entorno
cp .env.example .env
php artisan key:generate
# Configurar DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD en .env

# 4. Base de datos
php artisan migrate --seed

# 5. Frontend
npm install
npm run build

# 6. Servidor de desarrollo
php artisan serve
```

La aplicación queda disponible en `http://localhost:8000`.

**Credenciales de prueba:**

| Rol | Email | Contraseña |
|---|---|---|
| Admin | admin@alquiler.com | password |
| Cliente | maria@alquiler.com | password |

---

## Comandos Artisan personalizados

```bash
# Pre-cargar el catálogo de CarSpecs en caché (recomendado antes del primer uso)
php artisan catalog:build

# Importar vehículos desde la API a la base de datos
php artisan vehicles:import        # importa 70 por defecto
php artisan vehicles:import 50     # importa N vehículos
```

---

## Documentación

| Archivo | Contenido |
|---|---|
| [`Documentacion/sistema.md`](./Documentacion/sistema.md) | Arquitectura, base de datos, módulos, rutas, seguridad y decisiones técnicas |
| [`Documentacion/diseno-ui.md`](./Documentacion/diseno-ui.md) | Sistema de diseño: componentes, paleta de color, radios, efectos y justificación de uso |
