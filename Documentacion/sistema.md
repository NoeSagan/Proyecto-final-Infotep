# AutoAlquiler - Documentación del Sistema

Descripción técnica y funcional completa del sistema de alquiler de vehículos desarrollado como proyecto final del curso de Desarrollo Web con PHP en INFOTEP.

---

## 1. Visión general

AutoAlquiler es una aplicación web full-stack que digitaliza el proceso completo de una empresa de alquiler de vehículos: desde que un cliente encuentra el auto que busca hasta que el administrador registra la entrega y cierra la reserva. El sistema elimina los choques de reserva, automatiza el cálculo del costo, permite gestionar servicios adicionales y ofrece un panel de administración con métricas en tiempo real.

---

## 2. Stack tecnológico

| Capa | Tecnología | Versión |
|---|---|---|
| Lenguaje backend | PHP | 8.1+ |
| Framework backend | Laravel | 11 |
| Autenticación | Laravel Breeze | - |
| ORM | Eloquent | - |
| Base de datos | PostgreSQL | - (Supabase) |
| Motor de vistas | Blade | - |
| CSS framework | Tailwind CSS | 3.4 |
| Librería de UI | ddfsn/blade-components | 1.6.1 |
| Bundler frontend | Vite | 8.x |
| Runtime JavaScript | Alpine.js | 3.x |

**Base de datos en la nube:** Supabase (PostgreSQL gestionado), accedida desde Laravel mediante el driver `pgsql`. La configuración completa vive en el archivo `.env` y nunca se compromete al repositorio.

**Por qué PHP de sistema (`C:\php\php.exe`) en lugar de Herd Lite:** el PHP de Herd Lite no incluye la extensión `pgsql`. El intérprete en `C:\php` sí la tiene compilada, por lo que todos los comandos Artisan deben ejecutarse desde esa ruta.

---

## 3. Arquitectura del proyecto

El proyecto sigue la arquitectura MVC de Laravel con las siguientes capas:

```
app/
├── Console/Commands/       Comandos Artisan personalizados
├── Http/
│   ├── Controllers/        Lógica de cada ruta (Admin + Cliente)
│   ├── Middleware/         EnsureUserIsAdmin
│   └── Requests/           Validación de formularios (Form Requests)
├── Models/                 Eloquent Models con relaciones y métodos de negocio
├── Services/               Clientes de APIs externas
└── Themes/                 Configuración visual del sistema de diseño

resources/
├── css/                    app.css (Tailwind + CSS custom)
├── js/                     app.js (Alpine.js)
└── views/
    ├── admin/              Vistas del panel de administración
    ├── auth/               Login, registro, recuperación de contraseña
    ├── layouts/            Header, sidebar, footer, app.blade.php
    ├── reservas/           Flujo de reserva del cliente
    └── vehiculos/          Catálogo y ficha de vehículo
```

---

## 4. Base de datos

### 4.1 Tablas principales

| Tabla | Propósito |
|---|---|
| `users` | Clientes y administradores del sistema |
| `categories` | Categorías de vehículos (Sedán, SUV, Pickup, etc.) |
| `vehicles` | Inventario completo con ficha técnica |
| `extras` | Servicios adicionales contratables en una reserva |
| `reservations` | Reservas con estado y datos de entrega del vehículo |
| `reservation_extra` | Pivote: qué extras lleva cada reserva y en qué cantidad |
| `favorites` | Pivote: vehículos guardados por cada usuario |

### 4.2 Campos clave por tabla

**`users`**
- `name`, `email`, `password` - datos de autenticación
- `role` - `admin` o `cliente`

**`vehicles`**
- `brand`, `model`, `year`, `plate` - identificación
- `category_id` - relación con categoría
- `fuel_type` - `gasolina`, `diesel`, `hibrido`, `electrico`
- `transmission_type` - `automatica`, `manual`
- `passenger_capacity`, `luggage_capacity` - capacidades
- `price_per_day` - tarifa base
- `status` - `disponible`, `alquilado`, `mantenimiento`
- `image_url`, `external_id` - vehículos importados desde APIs externas
- `current_mileage`, `key_features`, `model_alternative` - ficha técnica

**`reservations`**
- `user_id`, `vehicle_id` - relaciones principales
- `start_date`, `end_date` - período de alquiler
- `passenger_count` - pasajeros declarados en la reserva
- `total_cost` - costo calculado al momento de la reserva
- `status` - `pendiente`, `confirmada`, `completada`, `cancelada`
- `delivery_plate`, `delivery_fuel_level`, `delivery_mileage` - datos registrados al confirmar el pago

**`extras`**
- `name`, `price` - nombre y tarifa diaria
- `selection_type` - `single` (checkbox, cantidad 1) o `multiple` (input numérico)

**`reservation_extra` (pivot)**
- `reservation_id`, `extra_id`, `quantity`

### 4.3 Relaciones Eloquent

```php
User         → hasMany Reservation
User         → belongsToMany Vehicle (favorites)
Category     → hasMany Vehicle
Vehicle      → belongsTo Category
Vehicle      → hasMany Reservation
Vehicle      → belongsToMany User (favorites)
Extra        → belongsToMany Reservation (pivot: quantity)
Reservation  → belongsTo User
Reservation  → belongsTo Vehicle
Reservation  → belongsToMany Extra (pivot: quantity)
```

---

## 5. Autenticación y roles

### 5.1 Autenticación

Gestionada por **Laravel Breeze** con stack Blade. Incluye:
- Registro de usuario (rol `cliente` por defecto)
- Login con redirección según rol
- Recuperación de contraseña por correo
- Gestión del perfil (nombre, email, contraseña)

### 5.2 Redirección post-login

```
Admin  → /admin   (panel de administración)
Cliente → /vehiculos (catálogo)
```

La redirección es incondicional para admins: no depende de ninguna URL "intended" almacenada en sesión, lo que evita que una sesión anterior desvíe al admin a una página incorrecta.

### 5.3 Middleware de administrador

`EnsureUserIsAdmin` verifica que el usuario autenticado tenga `role = 'admin'`. Si no, responde con HTTP 403. Se aplica a todo el grupo de rutas `/admin/*`.

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(...)
```

---

## 6. Módulos del sistema

### 6.1 Catálogo de vehículos (público)

**Ruta:** `GET /vehiculos`

Muestra todos los vehículos disponibles. Acepta filtros por:
- Fecha de inicio y fin (excluye vehículos con reservas solapadas en ese período)
- Número de pasajeros (filtra por `passenger_capacity >=`)
- Categoría, combustible, transmisión
- Presupuesto máximo por día

El catálogo opera sobre tres fuentes de datos en cascada:
1. **AutoScout24** (API externa con listings reales e imágenes) - cuota mensual, puede no estar disponible
2. **CarSpecs** (1 000+ modelos pre-cacheados en `carspecs_catalog_v2`, TTL 24 h) - fuente principal cuando AutoScout24 falla
3. **Base de datos local** - 29 vehículos sembrados + vehículos importados manualmente

Los vehículos de API se importan a la BD al primer acceso a su ficha (`import-on-demand`), recibiendo una placa generada (`EXT-{md5}`).

### 6.2 Ficha de vehículo

**Ruta:** `GET /vehiculos/{vehicle}` o `GET /vehiculos/listing/{listingId}`

Muestra la ficha técnica completa: marca, modelo, año, categoría, combustible, transmisión, capacidades, kilometraje, imagen y extras disponibles. Incluye:
- Botón de favorito (toggle) para usuarios autenticados
- Botón de reserva (redirige a login si el usuario no está autenticado)

### 6.3 Flujo de reserva

El proceso tiene tres pasos lineales, cada uno en su propia pantalla:

**Paso 1 - Formulario de reserva** (`GET /vehiculos/{vehicle}/reservar`)
El cliente selecciona fechas, número de pasajeros y extras. El costo total se calcula en tiempo real mediante JavaScript sin recargar la página. Al enviar, se crea la reserva en estado `pendiente`.

**Paso 2 - Pantalla de pago** (`GET /reservas/{reservation}/pago`)
Resumen completo de la reserva (vehículo, fechas, extras, total). El cliente puede confirmar el pago o guardarlo para después. Si confirma, el estado pasa a `confirmada` y se registran los datos de entrega del vehículo (placa real, nivel de combustible, kilometraje).

**Paso 3 - Comprobante** (`GET /mis-reservas/{reservation}`)
Vista de detalle de la reserva confirmada, con enlace para imprimir el comprobante.

### 6.4 Cancelación de reservas

Los clientes pueden cancelar una reserva desde su vista de detalle. La política de cargos es:

| Antelación a la fecha de inicio | Cargo |
|---|---|
| Más de 48 horas (o estado `pendiente`) | Gratuita (0 %) |
| Entre 24 y 48 horas | 25 % del total |
| Menos de 24 horas | 50 % del total |

El modal de confirmación muestra el desglose del cargo antes de confirmar. El cálculo lo realizan los métodos `cancellationFee()` y `cancellationFeePercent()` del modelo `Reservation`.

### 6.5 Favoritos

Los usuarios autenticados pueden guardar vehículos en su lista de favoritos. El toggle es inmediato (formulario POST sin JavaScript). La lista de favoritos se accede desde el menú "Mis Listas".

### 6.6 Panel de administración

Accesible solo para usuarios con `role = admin`. Incluye:

**Dashboard** (`/admin`)
- Métricas en tiempo real: reservas activas, vehículos disponibles, ingresos del mes, número de clientes.
- Tabla de últimas reservas.
- Accesos rápidos a las secciones principales.

**Vehículos** (`/admin/vehiculos`)
- CRUD completo: crear, listar, editar, eliminar.
- Lookup de VIN mediante la API de Auto.dev para autocompletar la ficha técnica.
- Registro de mantenimiento con bloqueo del vehículo durante ese período.

**Categorías** (`/admin/categorias`)
- CRUD de categorías. Al eliminar una categoría, los vehículos asociados pierden la categoría (comportamiento controlado en el modelo).

**Extras** (`/admin/extras`)
- CRUD de extras contratables. Cada extra define su tipo de selección (`single` / `multiple`).

**Reservas** (`/admin/reservas`)
- Listado completo con filtros por estado.
- Vista de detalle con posibilidad de cambiar el estado (`pendiente → confirmada`, `confirmada → completada`, o cancelar).
- Exportación a CSV del listado completo.

**Usuarios** (`/admin/usuarios`)
- Listado de todos los usuarios registrados.
- Vista de detalle con su historial de reservas.
- Cambio de rol (cliente ↔ admin).

**Reportes** (`/admin/reportes`)
- Vehículos más alquilados (por número de reservas completadas).
- Ingresos por mes (últimos 6 meses, sumando `total_cost` de reservas confirmadas/completadas).
- Ocupación por categoría (porcentaje de vehículos alquilados vs. total).

---

## 7. APIs externas

### 7.1 Auto.dev (`AutoDevService`)

**Uso:** `lookupByVin($vin)` - dado un número VIN, devuelve la ficha técnica del vehículo (marca, modelo, año, combustible, transmisión, capacidades). Se usa en el formulario de creación de vehículo del admin para autocompletar campos y reducir errores.

### 7.2 CarSpecs (`CarSpecsService`)

**Uso:** construye el catálogo de marcas y modelos con el comando `catalog:build`. El resultado (hasta 1 390 vehículos) se almacena en caché con clave `carspecs_catalog_v2` y TTL de 24 horas. Es la fuente principal del catálogo público cuando AutoScout24 no está disponible.

### 7.3 AutoScout24 (`AutoScout24Service`)

**Uso:** catálogo de listings reales con imágenes de stock. Requiere cuota mensual de la API de RapidAPI. Cuando está disponible, tiene prioridad sobre CarSpecs porque incluye imágenes y precios de mercado más reales.

### 7.4 Wikimedia Commons (`CarImagesService`)

**Uso:** `getFirstImage($make, $model)` - busca una imagen pública del vehículo en Wikimedia Commons. Se usa como fallback cuando el vehículo de CarSpecs no tiene imagen propia. Las imágenes se cachean 7 días para no superar el límite de peticiones de la API.

**`getBrandLogo($make)`** - obtiene el logo de la marca para mostrarlo en la landing page. También cacheado.

---

## 8. Comandos Artisan personalizados

| Comando | Descripción |
|---|---|
| `catalog:build` | Pre-carga el catálogo de CarSpecs en caché para acelerar el primer acceso al catálogo |
| `vehicles:import {count=70}` | Importa N vehículos desde el caché de CarSpecs a la base de datos, descargando imágenes de Wikimedia |

---

## 9. Rutas principales

### Públicas

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/` | Landing page con catálogo destacado y marcas |
| GET | `/vehiculos` | Catálogo con filtros |
| GET | `/vehiculos/{id}` | Ficha de vehículo (BD) |
| GET | `/vehiculos/listing/{id}` | Ficha de vehículo (API, import-on-demand) |
| GET | `/terminos` | Términos y condiciones |
| GET | `/privacidad` | Política de privacidad |
| GET | `/contacto` | Formulario de contacto |

### Autenticadas (cliente)

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/vehiculos/{v}/reservar` | Formulario de reserva |
| POST | `/vehiculos/{v}/reservar` | Guarda la reserva (estado: pendiente) |
| GET | `/reservas/{r}/pago` | Pantalla de confirmación de pago |
| POST | `/reservas/{r}/pago` | Confirma el pago y actualiza estado |
| GET | `/mis-reservas` | Listado de reservas del cliente |
| GET | `/mis-reservas/{r}` | Detalle de reserva |
| POST | `/mis-reservas/{r}/cancelar` | Cancela la reserva (con o sin cargo) |
| GET | `/mis-reservas/{r}/comprobante` | Vista imprimible del comprobante |
| GET | `/favoritos` | Lista de vehículos favoritos |
| POST | `/favoritos/{v}/toggle` | Agrega o elimina de favoritos |
| GET | `/perfil` | Edición del perfil del cliente |

### Administración (`/admin/*`)

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/admin` | Dashboard con métricas |
| CRUD | `/admin/vehiculos` | Gestión de inventario |
| CRUD | `/admin/categorias` | Gestión de categorías |
| CRUD | `/admin/extras` | Gestión de extras |
| GET | `/admin/reservas` | Listado de todas las reservas |
| GET | `/admin/reservas/{r}` | Detalle de reserva |
| PATCH | `/admin/reservas/{r}/estado` | Cambio de estado de reserva |
| GET | `/admin/reservas/exportar` | Exportar CSV |
| GET | `/admin/usuarios` | Listado de usuarios |
| PATCH | `/admin/usuarios/{u}/rol` | Cambiar rol |
| GET | `/admin/reportes` | Dashboard de reportes |

---

## 10. Seguridad

- **Autenticación:** todas las rutas sensibles están protegidas por el middleware `auth` de Laravel Breeze.
- **Autorización por rol:** el middleware `EnsureUserIsAdmin` protege cada ruta del panel de administración individualmente, no solo el acceso al área.
- **CSRF:** todos los formularios incluyen `@csrf`. Laravel valida el token en cada POST/PATCH/DELETE.
- **Validación de entrada:** se usan Form Requests (`StoreReservationRequest`, etc.) para validar y sanitizar todos los datos del usuario antes de procesarlos. Nunca se confía en el input sin validar.
- **Autorización de recursos propios:** los clientes solo pueden ver y gestionar sus propias reservas. El controlador verifica que `$reservation->user_id === auth()->id()` antes de mostrar cualquier detalle.
- **Protección contra doble pago:** el `PaymentController` verifica que la reserva esté en estado `pendiente` antes de procesarla; si ya fue confirmada, redirige sin error.

---

## 11. Decisiones de diseño técnico

**Un solo layout de aplicación.** Todas las vistas autenticadas usan `<x-app-layout>` que incluye el header, sidebar móvil y footer. Para así tener consistencia y simplifica el mantenimiento.

**Catálogo en tres capas.** El diseño en cascada (AutoScout24 → CarSpecs → BD local) hace que el catálogo funcione con o sin acceso a las APIs de pago. En un contexto de demostración o con cuota agotada, los vehículos sembrados y los importados manualmente garantizan contenido funcional.

**Import-on-demand de vehículos de API.** Los vehículos de fuentes externas no se vuelcan masivamente a la BD al inicio. Solo se persisten cuando un cliente accede a su ficha, eso reduce el uso de la base de datos y mantiene el catálogo actualizado con la fuente original.

**Costo calculado al momento de la reserva.** El campo `total_cost` se calcula y persiste en `reservations` cuando el cliente crea la reserva, no cuando paga. Si el precio por día del vehículo cambia después, la reserva mantiene el precio acordado originalmente.

**Estado de entrega en el pago, no en la reserva.** Los datos de entrega (`delivery_plate`, `delivery_fuel_level`, `delivery_mileage`) se guardan cuando el administrador confirma el pago, no cuando el cliente hace la reserva. Esto refleja el flujo de: el cliente reserva y paga, el administrador registra las condiciones físicas del vehículo en el momento de entregarlo.

---

## 12. Datos de prueba (seeders)

| Entidad | Cantidad | Credenciales |
|---|---|---|
| Administrador | 1 | `admin@alquiler.com` / `password` |
| Clientes | 3 | `maria@`, `carlos@`, `laura@` / `password` |
| Categorías | 6 | Sedán, SUV, Pickup, Compacto, Lujo, Eléctrico |
| Extras | 7 | GPS, Asiento bebés, Asistencia, Seguro, Portaequipajes, Portabicicletas, WiFi |
| Vehículos | 29 | Distribuidos entre categorías y estados |
| Reservas | Variadas | En distintos estados para demostrar el flujo completo |
