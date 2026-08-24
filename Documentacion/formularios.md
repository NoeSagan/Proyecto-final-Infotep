# Vistas, Formularios y Rutas - Sistema de Alquiler de Vehículos

Este documento lista todas las páginas del sistema organizadas por sección, junto con su ruta, método HTTP y una breve explicación de su función.

## 1. Vistas Públicas y de Autenticación

| Página | Ruta | Método | Descripción |
|---|---|---|---|
| Landing Page / Inicio | `/` | GET | Página de bienvenida con presentación general del servicio y acceso al catálogo. |
| Formulario de Registro | `/register` | GET / POST | Alta de un nuevo usuario (rol cliente por defecto). |
| Formulario de Login | `/login` | GET / POST | Inicio de sesión de usuarios existentes. |
| Solicitar recuperación de contraseña | `/forgot-password` | GET / POST | El usuario ingresa su correo para recibir el enlace de restablecimiento. |
| Restablecer contraseña | `/reset-password/{token}` | GET / POST | Formulario para definir una nueva contraseña a partir del enlace recibido. |
| Términos y condiciones | `/terminos` | GET | Página estática con la política de alquiler del servicio. |
| Página de error / 404 | - (fallback) | GET | Vista mostrada cuando se busca una placa, vehículo o reserva inexistente. |

## 2. Vistas y Formularios del Cliente

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

## 3. Vistas y Formularios del Administrador

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

## 4. Resumen por prioridad

Si el tiempo de desarrollo es limitado, este es el orden de importancia sugerido para las páginas agregadas sobre la lista original:

**Prioridad alta** (se recomienda no dejarlas fuera)
- Recuperar contraseña.
- Detalle de Reserva (cliente y admin).
- Perfil del Cliente.
- Gestión de Usuarios.

**Prioridad media/opcional** (suman valor pero pueden quedar para el final si falta tiempo)
- Términos y condiciones.
- Página de error / 404 personalizada.
- Reportes y Estadísticas.
- Registro de Mantenimiento.