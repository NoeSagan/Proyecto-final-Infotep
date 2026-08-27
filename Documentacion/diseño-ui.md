# Sistema de Diseño — AutoAlquiler

Justificación y guía de uso de los componentes de interfaz, la paleta de color, la tipografía de escala y los efectos visuales del sistema. Toda la capa de UI está construida sobre la librería **ddfsn/blade-components** integrada con **Tailwind CSS v3**.

---

## 1. Fundamentos de color

La paleta está pensada para transmitir confianza, claridad y modernidad. Se evitan los azules genéricos saturados y se prefieren tonos profundos que sitúan al contenido en primer plano.

| Rol | Hex | Texto sobre él |
|---|---|---|
| **Base 100** | `#f7f9fa` | — fondo general de página |
| **Base 200** | `#eef2f6` | — fondos de card y tabla |
| **Base 300** | `#dfe5ed` | — bordes y divisores |
| **Texto base** | `#0d1529` | — body copy, labels |
| **Primario** | `#000000` | `#ffffff` |
| **Secundario** | `#135bf9` | `#eff6ff` |
| **Accent** | `#000000` | `#ffffff` |
| **Neutral** | `#0d1529` | `#f7f9fa` |
| **Info** | `#0d4c62` | `#ebfdfe` |
| **Success** | `#004c39` | `#e9faf2` |
| **Warning** | `#ff8904` | `#421104` |
| **Error** | `#830c41` | `#fceeef` |

**Decisión de diseño:** el negro puro como color primario y accent elimina la distracción del color en acciones principales, mantiene el foco en el contenido y garantiza contraste WCAG AA en cualquier fondo claro. El azul (`#135bf9`) se reserva para el rol secundario: enlaces, indicadores de estado y llamadas a la acción secundarias, donde el color añade significado sin saturar la UI.

---

## 2. Radios y tamaños

Los radios se asignan por tipo de elemento para crear una jerarquía visual consistente. Los elementos de mayor tamaño (cards, modales) reciben el radio más grande para enmarcar el contenido; los elementos pequeños e interactivos (botones, inputs) comparten el mismo radio medio; los elementos de estado compacto (badges, checkboxes) usan el radio máximo para comunicar su naturaleza de "etiqueta".

| Grupo | Radio |
|---|---|
| Cards, modales, alertas | `1rem` |
| Botones, inputs, selects, tabs | `1rem` |
| Checkboxes, toggles, badges | `2rem` |

**Grosor de borde:** `1px` uniforme en todos los elementos con borde.

**Altura de control:** `4.0px` como base de escala para botones, inputs, selects y tabs, lo que mantiene una cuadrícula óptica consistente entre formularios y acciones.

---

## 3. Efectos de campo

Los inputs, selects y textareas aplican dos capas de efecto que refuerzan la percepción de profundidad y textura:

**Profundidad 3D:** sombra interior (`inset box-shadow`) con dos capas superpuestas de opacidad baja que simulan un ligero hundimiento del campo respecto a la superficie de la card. El ojo percibe el input como un espacio donde escribir, diferenciado del fondo plano.

**Noise:** textura fractal SVG (`feTurbulence` + `feColorMatrix` saturate 0) aplicada como `background-image` con `blend-mode: multiply` al 3 % de opacidad. Añade una sensación de papel o material físico sin romper la legibilidad. La opacidad es lo suficientemente baja para ser subconsciente: el usuario no la identifica pero percibe que la UI "tiene peso".

---

## 4. Layout y navegación

### Header (`<x-header>`)

Barra superior pegajosa (`sticky`) que contiene:
- **Izquierda:** botón hamburger en móvil + logo + navegación principal en escritorio.
- **Derecha:** menú de usuario autenticado (avatar + dropdown con perfil y cierre de sesión) o botones de login/registro.

El header usa `bg-[var(--sidebar)]` (blanco) con `shadow-md`. En móvil, el logo y la navegación principal se ocultan; el dropdown de usuario permanece visible.

```blade
<x-header sticky class="h-20 shadow-md">
    <!-- hamburger · logo · nav · user-menu -->
</x-header>
```

### Sidebar móvil (`<x-sidebar>`)

Panel lateral deslizante que se activa exclusivamente en pantallas `< lg`. Contiene los mismos enlaces de navegación del header desktop, organizados en acordeón por rol (Admin / Cliente / Legal). Se cierra mediante el botón X interno o tocando el backdrop oscuro.

```blade
<x-sidebar class="lg:hidden" sticky>
    <x-sidebar-toggle aria-label="Cerrar menú">
        <x-heroicon-o-x-mark />
    </x-sidebar-toggle>
    <x-accordion :exclusive="true">
        <!-- enlaces por sección -->
    </x-accordion>
</x-sidebar>
```

**Botón hamburger:** color `bg-[var(--primary)]` (negro) con icono blanco para máxima visibilidad sobre el header blanco.

---

## 5. Separador (`<x-separator>`)

Línea divisoria horizontal o vertical que separa bloques de acción relacionados pero distintos. En el header admin se usa entre el botón de descarga y el botón de exportación para indicar que son acciones del mismo contexto pero de distinta naturaleza.

```blade
<x-separator vertical />
```

**Cuándo usarlo:** entre grupos de botones dentro de una misma barra de herramientas, entre secciones dentro de un panel lateral, o para dividir bloques de información en tarjetas complejas.

---

## 6. Alertas (`<x-alert>`)

Las alertas guían al usuario sobre el resultado de sus acciones o sobre condiciones del sistema. Cada estilo tiene semántica propia y debe usarse de forma rigurosa para que el usuario aprenda a interpretar los colores.

| Estilo | Cuándo usarla |
|---|---|
| `default` | Información neutral sin urgencia (ej.: "Tu sesión fue restaurada") |
| `success` | Acción completada con éxito (ej.: "Vehículo guardado", "Reserva confirmada", "Contraseña actualizada") |
| `info` | Contexto útil no urgente (ej.: "Este vehículo incluye GPS de serie") |
| `warning` | Situación que requiere atención pero no bloquea (ej.: "Cancelar ahora conlleva un cargo del 25 %") |
| `danger` | Error o acción bloqueada (ej.: "No se pudo procesar el pago", "Fechas no disponibles") |

```blade
<x-alert style="success">Reserva confirmada correctamente.</x-alert>
<x-alert style="danger">Las fechas seleccionadas ya no están disponibles.</x-alert>
```

Las alertas se colocan al inicio del área de contenido principal, por encima del formulario o lista que las originó, y desaparecen al recargar la página (se emiten a través de `session()->flash()`).

---

## 7. Acordeón (`<x-accordion>`)

Contenedor de preguntas y respuestas o secciones colapsables. El modo `exclusive` garantiza que solo un ítem permanezca abierto a la vez, evitando la sobrecarga visual.

**Usos en el sistema:**

- **Página de contacto / FAQ:** ¿Cuál es la política de cancelación? ¿Cuáles son los métodos de pago aceptados? ¿Qué pasa si el vehículo tiene un accidente?
- **Ficha de vehículo:** la sección "Extras disponibles" usa un acordeón para no desplazar el botón de reserva más abajo de la pantalla.
- **Sidebar móvil:** cada grupo de enlaces (Admin, Cliente, Legal) es un ítem del acordeón.

```blade
<x-accordion :exclusive="true">
    <x-accordion.item :expanded="true">
        <x-accordion.title size="sm">¿Cuál es el costo de cancelación?</x-accordion.title>
        <x-accordion.content>
            Cancelaciones con más de 48 h de antelación son gratuitas. Entre 24 y 48 h se aplica
            un cargo del 25 %. Con menos de 24 h, el cargo es del 50 % del total.
        </x-accordion.content>
    </x-accordion.item>
</x-accordion>
```

---

## 8. Avatar (`<x-avatar>` / `<x-avatar.stack>`)

Representación visual de un usuario o de un grupo de usuarios.

**Usos en el sistema:**

- **Header / Dropdown de usuario:** avatar `sm` del usuario autenticado junto a su nombre.
- **Ficha de vehículo — social proof:** stack de avatares `xs` con un contador de tipo "3 personas lo están viendo" para generar urgencia de reserva.
- **Panel de admin — lista de usuarios:** avatar `sm` para identificar rápidamente a cada usuario en la tabla.

```blade
{{-- Stack de social proof --}}
<x-avatar.stack>
    <x-avatar size="xs" />
    <x-avatar size="xs" />
    <x-avatar size="xs" />
</x-avatar.stack>
<span class="text-xs text-muted-foreground">+3 personas lo están viendo</span>
```

Los tamaños disponibles son `xs`, `sm`, `default` y `lg`. En stacks se usa siempre el tamaño más pequeño posible para no robar protagonismo al contenido principal.

---

## 9. Breadcrumb (`<x-breadcrumb>`)

Indicador de posición jerárquica dentro de la aplicación. Orienta al usuario sobre el camino recorrido y le permite regresar a niveles anteriores con un clic.

**Cuándo usarlo:** en páginas de detalle (reserva individual, ficha de vehículo, perfil de usuario en admin), donde el usuario llegó desde un listado y podría querer volver.

```blade
<x-breadcrumb>
    <x-breadcrumb.item href="{{ route('admin.vehiculos.index') }}">Vehículos</x-breadcrumb.item>
    <x-breadcrumb.separator />
    <x-breadcrumb.item active>{{ $vehicle->brand }} {{ $vehicle->model }}</x-breadcrumb.item>
</x-breadcrumb>
```

No se usa en páginas de primer nivel (dashboard, listados principales) porque el usuario ya sabe dónde está.

---

## 10. Botones (`<x-btn>`)

Los botones son el elemento más frecuente de la UI y su estilo debe comunicar de forma instantánea la importancia y el tipo de acción.

| Estilo | Uso en AutoAlquiler |
|---|---|
| `primary` | Acción principal de la pantalla: "Reservar este vehículo", "Guardar cambios", "Confirmar y pagar" |
| `secondary` | Acción alternativa destacada: "Ver disponibilidad", "Exportar CSV" |
| `outline` | Acción secundaria junto a una primaria: "Pagar más tarde", "Volver", "Cancelar reserva" |
| `ghost` | Acciones terciarias o de navegación inline: enlaces del menú, iconos de acción en tablas |
| `success` | Confirmación explícita de una operación positiva: "Confirmar cancelación gratuita" |
| `info` | Enlace a documentación o contexto adicional |
| `warning` | Acción irreversible con consecuencias moderadas: "Aplicar descuento (expira hoy)" |
| `danger` | Acción destructiva: "Eliminar vehículo", "Eliminar cuenta", botón de cancelación con cargo |

**Regla de convivencia:** en cada pantalla o sección debe haber como máximo un botón `primary`. El resto son `outline` o `ghost`. Cuando la acción es destructiva e irreversible, se usa `danger` y se acompaña de un modal de confirmación.

---

## 11. Botón de estado de carga (`<x-spinner>`)

Se usa para representar operaciones asíncronas en curso, principalmente el procesamiento de pago. El botón se desactiva (`disabled`) y muestra el spinner para evitar dobles envíos.

```blade
<x-btn style="outline" disabled>
    <x-spinner />
    Procesando pago...
</x-btn>
```

**Cuándo usarlo:** al enviar el formulario de pago, al guardar cambios en un formulario largo, o al exportar un reporte. Se activa por JavaScript tras el primer clic y se elimina cuando la respuesta del servidor llega.

---

## 12. Grupo de botones (`<x-btn.group>`)

Agrupa botones relacionados en una unidad visual compacta. Comunica que las opciones son mutuamente excluyentes o que actúan sobre el mismo objeto.

**Usos en el sistema:**

- **Formulario de contacto / reporte:** toolbar de formato de texto (negrita, cursiva, tachado, alineación).
- **Filtros del catálogo:** grupo de opciones de combustible o transmisión como alternativa a un `<select>`.
- **Panel de admin — acciones de fila:** en tablas donde cada fila tiene múltiples acciones rápidas (Ver / Editar / Eliminar) agrupadas para ahorrar espacio.

```blade
<x-btn.group>
    <x-btn style="outline" size="icon"><x-heroicon-o-bold /></x-btn>
    <x-btn style="outline" size="icon"><x-heroicon-o-italic /></x-btn>
    <x-btn style="outline" size="icon"><x-heroicon-o-strikethrough /></x-btn>
</x-btn.group>
```

---

## 13. Card genérica (`<x-card>`)

Contenedor con cabecera, cuerpo y pie claramente separados. Aporta jerarquía visual y delimita el alcance de la información o el formulario que contiene.

**Usos prioritarios:**

- **Login, registro y restablecimiento de contraseña:** el formulario de autenticación dentro de una card centrada en pantalla es el patrón estándar de la industria. Transmite seguridad y focaliza la atención.
- **Resumen de reserva en el flujo de pago:** la card agrupa el detalle del vehículo, las fechas, los extras y el total antes de confirmar el pago.
- **Dashboard admin — métricas:** cada indicador clave (reservas activas, ingresos del mes, vehículos disponibles) vive en su propia card para facilitar el escaneo visual.

```blade
<x-card>
    <x-card.header><x-card.title>Iniciar sesión</x-card.title></x-card.header>
    <x-card.body><!-- campos del formulario --></x-card.body>
    <x-card.footer><!-- botón de envío --></x-card.footer>
</x-card>
```

---

## 14. Card de estado vacío o error (`<x-empty>`)

Pantalla de reemplazo cuando no hay contenido que mostrar o cuando la operación falló. Evita las páginas en blanco que desorientan al usuario.

**Usos en el sistema:**

- **Error 404:** la ruta solicitada no existe.
- **Error 403:** acceso denegado (un cliente intenta acceder al panel admin).
- **Listado vacío:** el usuario no tiene reservas todavía, el catálogo no devuelve resultados, el admin no ha creado vehículos aún.
- **Búsqueda sin resultados:** filtros del catálogo que no coinciden con ningún vehículo.

```blade
<x-empty
    title="No encontramos vehículos"
    description="Prueba con otras fechas o amplía los filtros de búsqueda.">
    <x-btn href="{{ route('vehiculos.index') }}" style="outline">Limpiar filtros</x-btn>
</x-empty>
```

---

## 15. Lista de grupo (`<x-list-group>`)

Listado estructurado de ítems relacionados, con soporte para botones y badges inline. Es más compacto y escaneable que una tabla cuando los datos son mayoritariamente cualitativos.

**Usos en el sistema:**

- **Dashboard admin:** listado de últimas reservas, usuarios recientes o vehículos en mantenimiento.
- **Ficha de vehículo — extras:** lista de servicios adicionales disponibles con su precio y tipo.
- **Perfil de usuario:** datos personales (nombre, correo, teléfono) presentados como campo–valor.
- **Panel de facturación:** historial de transacciones con badge de estado (Pagado, Pendiente, Reembolsado).

```blade
<x-list-group>
    <x-list-group.item title="Estado de reserva">
        <x-badge style="success">Confirmada</x-badge>
    </x-list-group.item>
    <x-list-group.item title="Vehículo">Toyota Corolla 2022</x-list-group.item>
    <x-list-group.item title="Total pagado">$ 245.00</x-list-group.item>
</x-list-group>
```

---

## 16. Tabla (`<x-table>`)

Estructura tabular para conjuntos de datos comparables. Soporta encabezado pegajoso, resaltado de filas y alineación de columnas.

**Usos en el sistema:**

- **Admin — listado de reservas, vehículos, usuarios, extras, categorías:** tabla principal de cada módulo CRUD.
- **Admin — reportes:** vehículos más alquilados, ingresos por mes, ocupación por categoría.
- **Cliente — Mis Reservas:** historial de reservas con estado, fechas y acciones rápidas.

```blade
<div class="overflow-x-auto">
    <x-table>
        <x-table-header>
            <x-table-row>
                <x-table-head>#</x-table-head>
                <x-table-head>Vehículo</x-table-head>
                <x-table-head align="end">Total</x-table-head>
            </x-table-row>
        </x-table-header>
        <x-table-body>
            <!-- filas -->
        </x-table-body>
    </x-table>
</div>
```

El wrapper `overflow-x-auto` es obligatorio para garantizar la responsividad en pantallas pequeñas sin romper el layout de la página.

---

## 17. Inputs y formularios (`<x-form-input>`)

Los campos de formulario siguen una anatomía coherente: label superior, campo con efecto 3D + noise, mensaje de error o ayuda inferior.

**Variantes disponibles y su uso:**

| Variante | Uso en AutoAlquiler |
|---|---|
| `type="text"` | Nombre, apellido, modelo de vehículo, número de placa |
| `type="email"` | Correo electrónico en login, registro y perfil |
| `type="password"` | Contraseña en login, registro y cambio de contraseña |
| `type="date"` | Fecha de inicio y fin en el filtro del catálogo y el formulario de reserva |
| `type="number"` | Cantidad de extras tipo `multiple`, número de pasajeros |
| Con `icon-prefix` (lupa) | Buscadores: catálogo de vehículos, listados del admin |
| Con `icon-suffix` (embudo) | Campo de filtro rápido |
| Con `prefix` (texto) | Campos de unidad: "$ " antes del precio por día |
| Con `suffix` (texto) | Dominio fijo: "@alquiler.com" en campos de correo de prueba |

```blade
<x-form-input type="date" name="start_date" label="Fecha de inicio" />
<x-form-input name="passengers" type="number" label="Pasajeros" />
```

---

## 18. Checkbox y Radio button (`<x-form-checkbox>`)

**Checkbox:** para opciones independientes que no se excluyen mutuamente.

- Aceptar términos y condiciones (obligatorio antes de confirmar la reserva).
- Suscribirse al boletín de novedades en el registro.
- Extras de tipo `single` en el formulario de reserva (GPS, seguro de ocupantes, asistencia en carretera, WiFi portátil).

**Radio button:** para opciones que se excluyen mutuamente (solo se puede elegir una).

- Selección de método de entrega (en sucursal / a domicilio) si se implementa en el futuro.
- Tipo de combustible como filtro en el catálogo (Todos / Gasolina / Diésel / Híbrido / Eléctrico).

```blade
<x-form-checkbox name="accept_terms" label="Acepto los términos y condiciones">
    <x-slot:description>
        <x-form-help>
            Al reservar, aceptas nuestra <a href="{{ route('terminos') }}">política de cancelación</a>.
        </x-form-help>
    </x-slot:description>
</x-form-checkbox>
```

---

## 19. Principios de composición

1. **Una acción principal por pantalla.** Cada vista debe tener un único botón `primary` claramente identificable. El resto de acciones usan `outline` o `ghost`.

2. **El color como semáforo.** Verde = éxito, rojo = error/peligro, amarillo = advertencia, azul = información. No se usan estos colores fuera de su semántica para evitar confusión.

3. **La card como unidad mínima de contexto.** Todo grupo de información relacionada (un formulario, un resumen, un panel de métricas) vive dentro de una card con borde y fondo diferenciado.

4. **Feedback inmediato.** Toda acción destructiva o irreversible (cancelar reserva, eliminar vehículo) está protegida por un modal de confirmación con `<dialog>` nativo y describe las consecuencias con claridad (cargo aplicado, datos que se perderán).

5. **Responsividad como requisito, no como añadido.** Todas las tablas se envuelven en `overflow-x-auto`. Los formularios de múltiples columnas usan `grid sm:grid-cols-2`. Los headers con múltiples acciones usan `flex-wrap gap-3`.
