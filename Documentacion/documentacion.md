# Sistema de Alquiler de Vehículos - Documentación Técnica

## 1. Descripción General del Proyecto

El Sistema de Alquiler de Vehículos es una aplicación web desarrollada en Laravel (PHP) que permite gestionar de forma completa el inventario de vehículos, la disponibilidad de fechas para reservas y la facturación asociada a cada alquiler. El objetivo principal del sistema es resolver el problema de las reservas superpuestas y facilitar el proceso de rentar un automóvil junto con servicios adicionales (extras).

El proyecto sigue estrictamente el patrón de arquitectura Modelo-Vista-Controlador (MVC), utilizando Eloquent ORM para la manipulación de datos, Blade para las vistas, y Composer para la gestión de dependencias.

## 2. Usuarios del Sistema

El sistema maneja sesiones de usuario autenticado y distingue dos perfiles con permisos y flujos diferentes:

### Cliente
- Se registra e inicia sesión en el sistema.
- Busca vehículos disponibles según sus necesidades.
- Selecciona un rango de fechas para el alquiler.
- Añade servicios extras a su reserva (GPS, silla infantil, seguros).
- Realiza y da seguimiento a sus reservas.

### Administrador
- Gestiona el inventario completo de vehículos (alta, edición, baja).
- Administra las categorías de vehículos (SUV, Sedán, etc.).
- Supervisa el estado general de las reservas del sistema.
- Tiene acceso a un dashboard con métricas reales del negocio.

## 3. Requerimientos Funcionales

### 3.1 Autenticación y Dashboard
Los usuarios pueden registrarse e iniciar sesión mediante el sistema de autenticación de Laravel. Una vez autenticados, acceden a un dashboard que muestra información real extraída de la base de datos, como reservas activas, vehículos disponibles y ganancias generadas.

### 3.2 Gestión de Inventario (CRUD)
El administrador cuenta con operaciones completas de creación, consulta, edición y eliminación sobre:
- **Vehículos**: marca, modelo, placa, precio por día.
- **Categorías**: SUV, Sedán, entre otras.

### 3.3 Lógica de Reservas
Es el núcleo funcional del sistema. El cliente selecciona un vehículo y un rango de fechas (fecha de inicio y fecha de fin). El sistema valida obligatoriamente que no existan choques con otras reservas del mismo vehículo en ese rango de fechas antes de confirmar la operación.

### 3.4 Cálculo de Costos
Al momento de reservar, el sistema calcula automáticamente el costo total de la siguiente manera:

```
Costo total = (precio_por_dia × cantidad_de_dias) + suma_de_extras_seleccionados
```

Los extras se seleccionan de forma independiente y su costo se suma al subtotal del alquiler. Ejemplos de servicios extras que debe contemplar el catálogo:

- GPS.
- Asiento para bebés.
- Asistencia en carretera (grúa y servicios en taller).
- Seguro de ocupantes / accidentes personales.
- Portaequipajes o portabicicletas.

### 3.5 Gestión de Estados
El sistema controla el ciclo de vida de los registros mediante estados definidos:

| Entidad | Estados posibles |
|---|---|
| Vehículo | Disponible, Reservado, En mantenimiento |
| Reserva | Pendiente, Confirmada, Completada, Cancelada |

### 3.6 Búsqueda y Filtros
El sistema ofrece al menos una funcionalidad de búsqueda por texto (por ejemplo, nombre o modelo del vehículo) y una funcionalidad de filtrado (por categoría o por estado).

### 3.7 Búsqueda Guiada por Fechas y Capacidad
Antes de ver el catálogo, el cliente tiene la opción (no obligatoria) de indicar:

- Fecha de inicio y fecha de fin del alquiler.
- Cantidad de personas que utilizarán el vehículo.

Estos campos son opcionales: si el cliente no los completa, el catálogo se muestra completo sin restricciones. Si el cliente sí los completa, se usan únicamente para limitar/filtrar la búsqueda, mostrando solo los vehículos que cumplen ambas condiciones:

1. **Disponibilidad**: el vehículo no tiene ninguna reserva confirmada o pendiente que se solape con el rango de fechas indicado.
2. **Capacidad**: la capacidad de pasajeros del vehículo es igual o mayor a la cantidad de personas indicada.

El cliente puede ver y navegar las opciones de vehículos en cualquier momento, con o sin haber indicado fechas y pasajeros.

### 3.8 Lista de Favoritos
Cada cliente puede marcar vehículos como favoritos para guardarlos y consultarlos más adelante sin necesidad de repetir la búsqueda. Un vehículo puede estar en la lista de favoritos de muchos usuarios distintos, y un usuario puede tener muchos vehículos favoritos (relación `belongsToMany` entre `User` y `Vehicle`).

### 3.9 Información Detallada del Vehículo
La ficha de cada vehículo debe mostrar información ampliada, no solo marca/modelo/placa/precio. Como mínimo debe incluir:

- **Categoría o gama** (económico, intermedio, SUV, premium, etc.).
- **Modelo o similar** (referencia de modelo equivalente si no hay disponibilidad exacta).
- **Tipo de transmisión** (manual o automática).
- **Combustible / propulsión** (gasolina, diésel, híbrido, eléctrico).
- **Capacidad** (cantidad de pasajeros, y opcionalmente cantidad de maletas).
- **Prestaciones clave** (aire acondicionado, bluetooth, cámara de reversa, control crucero, etc.).

### 3.10 Entrega de Información al Confirmar el Pago
Una vez el cliente completa el pago y la reserva pasa a estado "Confirmada", el sistema debe entregarle la siguiente información específica del vehículo asignado, registrada en el momento de la entrega:

- Matrícula / placa del vehículo.
- Kilometraje inicial al momento de la entrega.
- Nivel de carga (vehículos eléctricos) o nivel de combustible al momento de la entrega.

## 4. Especificaciones Técnicas

| Aspecto | Detalle |
|---|---|
| Framework | Laravel (PHP), gestionado con Composer |
| Arquitectura | MVC estricto - la lógica de negocio reside en controladores, nunca en las rutas |
| Base de datos | Estructurada completamente mediante migraciones (`php artisan migrate`) |
| ORM | Eloquent, con relaciones `hasMany`, `belongsTo` y `belongsToMany` |
| Rutas | Verbos HTTP correctos (GET, POST, PUT/PATCH, DELETE) |
| Seguridad | Middleware para proteger secciones privadas; protección CSRF en formularios |
| Validación | Campos obligatorios, fechas y montos validados antes de almacenarse; conservación de valores antiguos (`old()`) ante errores |
| Interfaz | Vistas Blade con layouts y componentes reutilizables, mensajes de éxito/error, Tailwind CSS o Bootstrap |

## 5. Modelo de Datos

### 5.1 Entidades

- **User**: usuario del sistema (cliente o administrador).
- **Category**: categoría a la que pertenece un vehículo.
- **Vehicle**: vehículo disponible para alquiler, con ficha técnica ampliada.
- **Reservation**: reserva realizada por un usuario sobre un vehículo.
- **Extra**: servicio adicional que puede añadirse a una reserva.
- **reservation_extra**: tabla pivote que conecta reservas con sus extras seleccionados.
- **Favorite**: tabla pivote que conecta usuarios con sus vehículos favoritos.

### 5.2 Estructura de tablas

**users** (extendida)
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| name | string | Nombre del usuario |
| email | string | Correo electrónico único |
| password | string | Contraseña encriptada |
| role | enum | `admin` o `cliente` |
| timestamps | - | created_at / updated_at |

**categories**
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| name | string | Nombre de la categoría |
| description | text (nullable) | Descripción opcional |
| timestamps | - | created_at / updated_at |

**vehicles**
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| category_id | foreignId | Relación con categories |
| brand | string | Marca del vehículo |
| model | string | Modelo del vehículo |
| model_alternative | string (nullable) | Modelo o similar, referencia equivalente |
| plate | string (unique) | Placa del vehículo |
| price_per_day | decimal(8,2) | Precio por día de alquiler |
| status | enum | disponible / reservado / en_mantenimiento |
| transmission_type | enum | manual / automatica |
| fuel_type | enum | gasolina / diesel / hibrido / electrico |
| passenger_capacity | integer | Cantidad máxima de pasajeros |
| luggage_capacity | integer (nullable) | Cantidad máxima de maletas |
| key_features | text o json | Prestaciones clave (aire acondicionado, bluetooth, cámara de reversa, etc.) |
| current_mileage | integer | Kilometraje actual del vehículo |
| current_fuel_level | integer | Nivel de combustible o carga actual (%) |
| timestamps | - | created_at / updated_at |

**favorites** (pivote)
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| user_id | foreignId | Relación con users |
| vehicle_id | foreignId | Relación con vehicles |
| timestamps | - | created_at / updated_at |

**extras**
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| name | string | Nombre del extra |
| price | decimal(8,2) | Precio del extra |
| timestamps | - | created_at / updated_at |

**reservations**
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| user_id | foreignId | Relación con users |
| vehicle_id | foreignId | Relación con vehicles |
| start_date | date | Fecha de inicio del alquiler |
| end_date | date | Fecha de fin del alquiler |
| passenger_count | integer | Cantidad de personas que usarán el vehículo (usado en la búsqueda) |
| total_cost | decimal(10,2) | Costo total calculado |
| status | enum | pendiente / confirmada / completada / cancelada |
| delivery_plate | string (nullable) | Placa entregada al confirmar el pago |
| delivery_mileage | integer (nullable) | Kilometraje inicial registrado en la entrega |
| delivery_fuel_level | integer (nullable) | Nivel de combustible o carga registrado en la entrega |
| timestamps | - | created_at / updated_at |

> Los campos `delivery_*` se completan en el momento en que la reserva pasa a estado "Confirmada" (post-pago), tomando como base los valores actuales del vehículo (`current_mileage`, `current_fuel_level`, `plate`).

**reservation_extra** (pivote)
| Campo | Tipo | Descripción |
|---|---|---|
| id | bigint | Identificador único |
| reservation_id | foreignId | Relación con reservations |
| extra_id | foreignId | Relación con extras |
| quantity | integer | Cantidad del extra seleccionado |
| timestamps | - | created_at / updated_at |

### 5.3 Relaciones entre entidades

- Una **Category** tiene muchos **Vehicle** (`hasMany`).
- Un **Vehicle** pertenece a una **Category** (`belongsTo`).
- Un **User** tiene muchas **Reservation** (`hasMany`).
- Una **Reservation** pertenece a un **User** (`belongsTo`).
- Un **Vehicle** tiene muchas **Reservation** (`hasMany`).
- Una **Reservation** pertenece a un **Vehicle** (`belongsTo`).
- Una **Reservation** tiene muchos **Extra**, y un **Extra** puede pertenecer a muchas **Reservation** (`belongsToMany`, a través de la tabla pivote `reservation_extra`).
- Un **User** tiene muchos **Vehicle** favoritos, y un **Vehicle** puede ser favorito de muchos **User** (`belongsToMany`, a través de la tabla pivote `favorites`).

### 5.4 Diagrama entidad-relación (representación textual)

```
Category (1) ────────< (N) Vehicle >──────── (N) User
                              │           (belongsToMany
                              │            vía favorites)
                              │ (1)
                              │
                              ∨ (N)
User (1) ────────────< (N) Reservation >──────── (N) Extra
                                              (belongsToMany
                                          vía reservation_extra)
```

## 6. Modelos Eloquent

| Modelo | Campos fillable | Relaciones |
|---|---|---|
| Category | name, description | vehicles() → hasMany(Vehicle) |
| Vehicle | category_id, brand, model, plate, price_per_day, status | category() → belongsTo(Category), reservations() → hasMany(Reservation) |
| Extra | name, price | reservations() → belongsToMany(Reservation) |
| Reservation | user_id, vehicle_id, start_date, end_date, total_cost, status | user() → belongsTo(User), vehicle() → belongsTo(Vehicle), extras() → belongsToMany(Extra) |
| User (extendido) | name, email, password, role | reservations() → hasMany(Reservation), favoriteVehicles() → belongsToMany(Vehicle) |
| Favorite (pivote) | user_id, vehicle_id | user() → belongsTo(User), vehicle() → belongsTo(Vehicle) |

## 7. Flujo General del Sistema

1. El usuario se registra o inicia sesión.
2. Si es cliente, accede al catálogo de vehículos. De forma opcional puede indicar el rango de fechas y la cantidad de personas para limitar los resultados a los vehículos disponibles y con capacidad suficiente; si no lo hace, ve el catálogo completo.
3. El cliente revisa la ficha detallada de cada vehículo (categoría, transmisión, combustible, capacidad, prestaciones) y puede marcarlo como favorito.
4. El cliente selecciona el vehículo y define (o confirma) el rango de fechas del alquiler.
5. El sistema valida la disponibilidad del vehículo para ese rango de fechas.
6. El cliente selecciona los extras deseados.
7. El sistema calcula el costo total (días × precio_por_día + suma de extras).
8. Se crea la reserva con estado inicial "Pendiente" y el cliente realiza el pago.
9. Al confirmarse el pago, la reserva pasa a estado "Confirmada" y el sistema entrega al cliente la matrícula/placa, el kilometraje inicial y el nivel de combustible o carga del vehículo asignado.
10. El administrador puede completar o cancelar la reserva desde su panel según corresponda.
11. El estado del vehículo se actualiza en función del estado de la reserva.

## 8. Vistas, Formularios y Rutas del Sistema

### 8.1 Vistas Públicas y de Autenticación

| Página | Ruta | Método | Descripción |
|---|---|---|---|
| Landing Page / Inicio | `/` | GET | Página de bienvenida con presentación general del servicio y acceso al catálogo. |
| Formulario de Registro | `/register` | GET / POST | Alta de un nuevo usuario (rol cliente por defecto). |
| Formulario de Login | `/login` | GET / POST | Inicio de sesión de usuarios existentes. |
| Solicitar recuperación de contraseña | `/forgot-password` | GET / POST | El usuario ingresa su correo para recibir el enlace de restablecimiento. |
| Restablecer contraseña | `/reset-password/{token}` | GET / POST | Formulario para definir una nueva contraseña a partir del enlace recibido. |
| Términos y condiciones | `/terminos` | GET | Página estática con la política de alquiler del servicio. |
| Página de error / 404 | - (fallback) | GET | Vista mostrada cuando se busca una placa, vehículo o reserva inexistente. |

### 8.2 Vistas y Formularios del Cliente

Todas las rutas de esta sección requieren autenticación (`middleware('auth')`).

| Página | Ruta | Método | Descripción |
|---|---|---|---|
| Catálogo y Búsqueda | `/vehiculos` | GET | Listado de vehículos disponibles, con ficha resumida de cada uno. |
| Formulario de búsqueda/filtro | `/vehiculos` (query params) | GET | Filtros opcionales de fecha de inicio, fecha de fin, cantidad de pasajeros y categoría, incluidos como parámetros de la misma ruta del catálogo. |
| Detalle del Vehículo | `/vehiculos/{vehicle}` | GET | Ficha completa: categoría, transmisión, combustible, capacidad, prestaciones clave y disponibilidad. |
| Formulario de Reserva | `/vehiculos/{vehicle}/reservar` | GET / POST | Selección de fechas, cantidad de pasajeros y extras; envía los datos para calcular el costo total. |
| Pantalla de Confirmación / Pago | `/reservas/{reservation}/pago` | GET / POST | Resumen del costo total y simulación del pago; al confirmarse cambia el estado de la reserva. |
| Mis Reservas | `/mis-reservas` | GET | Listado de todas las reservas del cliente autenticado, con su estado actual. |
| Detalle de una Reserva | `/mis-reservas/{reservation}` | GET | Vista individual con el desglose de extras, costo total y, si ya está confirmada, los datos de entrega (placa, kilometraje, nivel de combustible). |
| Lista de Favoritos | `/favoritos` | GET | Vehículos que el cliente ha marcado como favoritos. |
| Agregar/quitar Favorito | `/favoritos/{vehicle}` | POST / DELETE | Alterna el estado de favorito de un vehículo específico. |
| Perfil del Cliente | `/perfil` | GET / PUT | Edición de nombre, correo y contraseña del usuario autenticado. |

### 8.3 Vistas y Formularios del Administrador

Todas las rutas de esta sección requieren autenticación y el middleware de rol administrador (`middleware(['auth', 'admin'])`).

| Página | Ruta | Método | Descripción |
|---|---|---|---|
| Dashboard Admin | `/admin` | GET | Métricas reales: reservas activas, vehículos disponibles y ganancias generadas. |
| Gestión de Categorías (Index) | `/admin/categorias` | GET | Listado de categorías existentes. |
| Formulario Crear Categoría | `/admin/categorias/crear` | GET / POST | Alta de una nueva categoría. |
| Formulario Editar Categoría | `/admin/categorias/{category}/editar` | GET / PUT | Edición de una categoría existente. |
| Eliminar Categoría | `/admin/categorias/{category}` | DELETE | Elimina una categoría del sistema. |
| Gestión de Vehículos (Index) | `/admin/vehiculos` | GET | Listado del inventario completo de vehículos. |
| Formulario Crear Vehículo | `/admin/vehiculos/crear` | GET / POST | Alta de un vehículo con toda su ficha técnica ampliada. |
| Formulario Editar Vehículo | `/admin/vehiculos/{vehicle}/editar` | GET / PUT | Edición de los datos del vehículo, incluyendo estado, kilometraje y nivel de combustible actuales. |
| Eliminar Vehículo | `/admin/vehiculos/{vehicle}` | DELETE | Elimina un vehículo del inventario. |
| Gestión de Extras (Index) | `/admin/extras` | GET | Listado de servicios extras disponibles. |
| Formulario Crear Extra | `/admin/extras/crear` | GET / POST | Alta de un nuevo servicio extra. |
| Formulario Editar Extra | `/admin/extras/{extra}/editar` | GET / PUT | Edición de un servicio extra existente. |
| Eliminar Extra | `/admin/extras/{extra}` | DELETE | Elimina un servicio extra del catálogo. |
| Gestión de Reservas (Index) | `/admin/reservas` | GET | Listado general de reservas con filtro por estado. |
| Detalle de Reserva (Admin) | `/admin/reservas/{reservation}` | GET | Vista completa de una reserva para revisar antes de confirmar, completar o cancelar. |
| Cambiar Estado de Reserva | `/admin/reservas/{reservation}/estado` | PUT / PATCH | Actualiza el estado de la reserva (confirmada, completada, cancelada). |
| Gestión de Usuarios (Index) | `/admin/usuarios` | GET | Listado de clientes registrados. |
| Detalle de Usuario | `/admin/usuarios/{user}` | GET | Historial de reservas de un cliente específico y opción de cambiar su rol. |
| Reportes y Estadísticas | `/admin/reportes` | GET | Vehículos más alquilados, ingresos por período y ocupación por categoría. |
| Registro de Mantenimiento | `/admin/vehiculos/{vehicle}/mantenimiento` | GET / POST | Anota el motivo y la fecha estimada de vuelta a disponible cuando un vehículo pasa a "En mantenimiento". |

### 8.4 Prioridad de las páginas

**Prioridad alta** (no se recomienda dejarlas fuera)
- Recuperar contraseña.
- Detalle de Reserva (cliente y admin).
- Perfil del Cliente.
- Gestión de Usuarios.

**Prioridad media/opcional** (suman valor pero pueden quedar para el final si falta tiempo)
- Términos y condiciones.
- Página de error / 404 personalizada.
- Reportes y Estadísticas.
- Registro de Mantenimiento.

## 9. Próximos pasos sugeridos

- Implementar el controlador de reservas con la validación de disponibilidad de fechas.
- Definir las rutas protegidas por middleware según el rol del usuario (admin/cliente).
- Construir las vistas Blade del catálogo, formulario de reserva y dashboard.
- Implementar las validaciones de formularios (Form Requests) para vehículos y reservas.