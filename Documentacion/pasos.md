# Paso a Paso - Desarrollo del Sistema de Alquiler de Vehículos

Esta guía describe el orden recomendado para construir el proyecto de principio a fin, indicando en cada paso qué páginas y rutas concretas se construyen. Cada paso depende del anterior, así que conviene seguirlos en secuencia: la base de datos debe estar sólida antes de tocar las vistas, y las vistas antes de pulir el dashboard.

## Paso 1 - Configurar el proyecto Laravel

Antes de escribir cualquier lógica de negocio hay que dejar el entorno listo.

- Crear el proyecto con Composer (`composer create-project laravel/laravel nombre-proyecto`).
- Configurar el archivo `.env` con los datos de conexión a la base de datos (PostgreSQL).
- Instalar un paquete de autenticación como Laravel Breeze, que ya trae registro, login, logout, recuperación de contraseña y un layout base en Blade. Esto cubre de una vez varias páginas del listado:
  - Formulario de Registro (`/register`).
  - Formulario de Login (`/login`).
  - Solicitar recuperación de contraseña (`/forgot-password`).
  - Restablecer contraseña (`/reset-password/{token}`).
- Confirmar que el proyecto corre correctamente antes de seguir (`php artisan serve`).

## Paso 2 - Crear las migraciones

Con el proyecto ya funcionando, se construye la estructura de la base de datos completa, en este orden:

1. Migración que agrega el campo `role` a la tabla `users` (admin / cliente).
2. `categories`.
3. `vehicles`, incluyendo todos los campos técnicos ampliados: categoría, modelo o similar, transmisión, combustible, capacidad de pasajeros y maletas, prestaciones clave, kilometraje actual y nivel de combustible actual.
4. `extras`.
5. `reservations`, incluyendo los campos de entrega (placa, kilometraje inicial, nivel de combustible al momento de la entrega) y la cantidad de pasajeros de la reserva.
6. Las tablas pivote `reservation_extra` y `favorites`.

El orden importa porque cada migración con `foreignId` depende de que la tabla referenciada ya exista. Al terminar, se ejecuta `php artisan migrate` y se revisa en la base de datos que todas las tablas se hayan creado correctamente.

## Paso 3 - Crear los modelos Eloquent

Con las tablas ya creadas, se generan los modelos correspondientes: `User` (extendido), `Category`, `Vehicle`, `Extra`, `Reservation` y `Favorite`.

En cada uno se definen:

- El arreglo `$fillable` con los campos que se podrán asignar de forma masiva.
- Las relaciones (`hasMany`, `belongsTo`, `belongsToMany`) según lo documentado previamente.

Este paso es clave porque todo el resto del sistema (controladores, vistas, dashboard) va a depender de que estas relaciones estén bien definidas desde el inicio.

## Paso 4 - Configurar roles y middleware

Antes de construir cualquier pantalla de administración hay que decidir quién puede verla.

- Crear un middleware (por ejemplo `EnsureUserIsAdmin`) que verifique el campo `role` del usuario autenticado.
- Registrar el middleware en `app/Http/Kernel.php` y aplicarlo a todas las rutas bajo `/admin/*`.
- Probar que un usuario cliente no pueda acceder a las rutas de administrador, y viceversa que el administrador sí tenga acceso.
- Crear también la vista de página de error / 404 general, que se usará en todo el sistema cuando se busque una placa, vehículo o reserva inexistente.

## Paso 5 - CRUD del administrador

Con los roles ya protegidos, se construye la gestión completa de categorías, vehículos y extras. Cada una sigue el mismo patrón de rutas:

| Página | Ruta | Método |
|---|---|---|
| Gestión de Categorías (Index) | `/admin/categorias` | GET |
| Formulario Crear Categoría | `/admin/categorias/crear` | GET / POST |
| Formulario Editar Categoría | `/admin/categorias/{category}/editar` | GET / PUT |
| Eliminar Categoría | `/admin/categorias/{category}` | DELETE |
| Gestión de Vehículos (Index) | `/admin/vehiculos` | GET |
| Formulario Crear Vehículo | `/admin/vehiculos/crear` | GET / POST |
| Formulario Editar Vehículo | `/admin/vehiculos/{vehicle}/editar` | GET / PUT |
| Eliminar Vehículo | `/admin/vehiculos/{vehicle}` | DELETE |
| Gestión de Extras (Index) | `/admin/extras` | GET |
| Formulario Crear Extra | `/admin/extras/crear` | GET / POST |
| Formulario Editar Extra | `/admin/extras/{extra}/editar` | GET / PUT |
| Eliminar Extra | `/admin/extras/{extra}` | DELETE |

Para cada entidad se necesita: controlador con los métodos `index`, `create`, `store`, `edit`, `update` y `destroy`; formularios Blade con validación de datos (Form Requests) y conservación de valores antiguos ante errores; y mensajes de éxito o error tras cada acción.

## Paso 6 - Búsqueda, disponibilidad y capacidad

Aquí se construye el catálogo que verá el cliente, con estas páginas:

| Página | Ruta | Método |
|---|---|---|
| Catálogo y Búsqueda | `/vehiculos` | GET |
| Formulario de búsqueda/filtro | `/vehiculos` (query params) | GET |
| Detalle del Vehículo | `/vehiculos/{vehicle}` | GET |

- El formulario de búsqueda/filtro no es una página aparte: son los mismos filtros opcionales (fecha de inicio, fecha de fin y cantidad de pasajeros) enviados como parámetros de la ruta del catálogo. Si el cliente no los completa, ve el catálogo completo sin restricciones.
- La consulta Eloquent del catálogo, cuando sí se indican fechas, descarta los vehículos con reservas confirmadas o pendientes que se solapen con ese rango, y filtra por capacidad mostrando solo vehículos cuyo `passenger_capacity` sea igual o mayor a la cantidad de personas indicada.
- El Detalle del Vehículo muestra la ficha completa: categoría, modelo o similar, transmisión, combustible, capacidad y prestaciones clave.

## Paso 7 - Flujo de reserva completa

Con el catálogo funcionando, se arma el proceso de principio a fin, cubriendo estas páginas:

| Página | Ruta | Método |
|---|---|---|
| Formulario de Reserva | `/vehiculos/{vehicle}/reservar` | GET / POST |
| Pantalla de Confirmación / Pago | `/reservas/{reservation}/pago` | GET / POST |
| Mis Reservas | `/mis-reservas` | GET |
| Detalle de una Reserva (Cliente) | `/mis-reservas/{reservation}` | GET |
| Lista de Favoritos | `/favoritos` | GET |
| Agregar/quitar Favorito | `/favoritos/{vehicle}` | POST / DELETE |

- El Formulario de Reserva incluye selección de extras (asiento para bebés, asistencia en carretera, seguro de ocupantes, portaequipajes, etc.) y calcula el costo total (días × precio por día + suma de extras).
- Al enviarse, se crea la reserva en estado "Pendiente" y se redirige a la Pantalla de Confirmación / Pago.
- Al confirmarse el pago, la reserva pasa a estado "Confirmada" y se registran los datos de entrega (placa, kilometraje inicial y nivel de combustible o carga), tomados de los valores actuales del vehículo.
- El Detalle de una Reserva muestra el desglose completo de extras, costo total y, si ya está confirmada, los datos de entrega.
- Favoritos permite marcar o desmarcar un vehículo desde el catálogo o el detalle, y consultar la lista guardada en `/favoritos`.

## Paso 8 - Dashboard, panel de administración avanzado y pulido final

Como último paso se completa la experiencia general del sistema, incluyendo las páginas restantes:

| Página | Ruta | Método |
|---|---|---|
| Landing Page / Inicio | `/` | GET |
| Perfil del Cliente | `/perfil` | GET / PUT |
| Dashboard Admin | `/admin` | GET |
| Gestión de Reservas (Index) | `/admin/reservas` | GET |
| Detalle de Reserva (Admin) | `/admin/reservas/{reservation}` | GET |
| Cambiar Estado de Reserva | `/admin/reservas/{reservation}/estado` | PUT / PATCH |
| Gestión de Usuarios (Index) | `/admin/usuarios` | GET |
| Detalle de Usuario | `/admin/usuarios/{user}` | GET |
| Términos y condiciones | `/terminos` | GET |
| Reportes y Estadísticas | `/admin/reportes` | GET |
| Registro de Mantenimiento | `/admin/vehiculos/{vehicle}/mantenimiento` | GET / POST |

- El Dashboard Admin muestra datos reales obtenidos de la base de datos: reservas activas, vehículos disponibles y ganancias generadas.
- Gestión de Reservas permite al administrador ver el listado general con filtro por estado, entrar al Detalle de Reserva y cambiar su estado (confirmada, completada, cancelada).
- Gestión de Usuarios lista los clientes registrados y permite ver su historial de reservas o cambiar su rol desde el Detalle de Usuario.
- La Landing Page, el Perfil del Cliente y los Términos y condiciones son páginas de contenido más simple, por lo que conviene dejarlas para el final junto con el resto del pulido visual.
- Reportes y Estadísticas y Registro de Mantenimiento son las páginas de menor prioridad: se construyen si queda tiempo disponible después de cubrir el resto del listado.
- Se cierra el desarrollo con una revisión final de validaciones, protección CSRF, conservación de valores antiguos y manejo correcto de los estados de vehículos y reservas en todo el sistema.

## Por qué este orden

Construir en este orden evita retrabajo: si se empiezan las vistas antes de tener migraciones y modelos definitivos, cualquier cambio en la estructura de datos obliga a rehacer consultas y formularios ya construidos. Dejar el dashboard, el panel avanzado de administración y el pulido visual para el final permite que esas pantallas reflejen datos reales del sistema ya funcional, en lugar de datos de prueba que después hay que reemplazar.