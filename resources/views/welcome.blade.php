<x-public-layout>
    <x-slot:title>Alquiler de Vehículos</x-slot:title>

{{-- ===== HERO ===== --}}
<section class="bg-gray-900 py-16 relative z-[60] shadow-md">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-10 items-center">

            <div class="flex-1 text-white">
                <p class="text-sm font-medium mb-3 text-gray-400 uppercase tracking-widest">Sistema de Alquiler</p>
                <h1 class="text-4xl sm:text-5xl font-bold mb-5 leading-tight">
                    Encuentra tu vehículo ideal,<br>
                    cuando lo necesitas
                </h1>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('vehiculos.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[var(--primary)] text-[var(--primary-foreground)] rounded-[var(--radius)] text-sm font-medium hover:opacity-90 transition-opacity">
                        Ver catálogo
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white border border-white/30 rounded-[var(--radius)] hover:bg-white/10 transition-colors">
                            Crear cuenta gratis
                        </a>
                    @endguest
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl w-full lg:w-80 shrink-0 p-5">
                <h2 class="font-bold text-gray-900 mb-1">Busca tu vehículo</h2>
                <p class="text-xs text-gray-400 mb-4">Filtra por marca, fechas y categoría.</p>
                <form action="{{ route('vehiculos.index') }}" method="GET" class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Marca o modelo</label>
                        <input type="text" name="search" placeholder="Toyota, Honda, BMW..."
                               class="w-full border border-gray-200 rounded-lg bg-white px-3 h-8 text-sm placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 text-gray-900">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Desde</label>
                            <input type="date" name="start_date" min="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg bg-white px-3 h-8 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 text-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Hasta</label>
                            <input type="date" name="end_date" min="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg bg-white px-3 h-8 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 text-gray-900">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Categoría</label>
                        <select name="category_id"
                                class="w-full border border-gray-200 rounded-lg bg-white px-3 h-8 text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 text-gray-900">
                            <option value="">Todas las categorías</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center h-8 px-3 bg-[var(--primary)] text-[var(--primary-foreground)] rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                        Buscar vehículos
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

{{-- ===== VEHÍCULOS DESTACADOS ===== --}}
@if ($featured->isNotEmpty())
<section class="py-16 bg-white relative z-[50] shadow-md">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Disponibles ahora</p>
            <h2 class="text-2xl font-bold text-gray-900">Vehículos favoritos de nuestros clientes</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach ($featured as $vehicle)
                <a href="{{ route('vehiculos.show', $vehicle) }}"
                   class="group block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-200 hover:border-gray-300">
                    <div class="relative bg-gray-100 h-40 overflow-hidden">
                        @if ($vehicle->image_url)
                            <img src="{{ $vehicle->image_url }}"
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full\'><svg class=\'w-10 h-10 text-gray-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zm-13-4h14l1-5H5l1 5z\'/></svg></div>'">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zm-13-4h14l1-5H5l1 5z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2.5 left-2.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/90 text-gray-700 shadow-sm">
                                {{ $vehicle->category->name }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="font-semibold text-gray-900 text-sm mb-0.5">
                            {{ $vehicle->brand }} {{ $vehicle->model }}
                        </p>
                        <p class="text-xs text-gray-400 mb-3">
                            {{ $vehicle->year ? $vehicle->year . ' · ' : '' }}{{ ucfirst($vehicle->transmission_type) }} · {{ $vehicle->passenger_capacity }} pas.
                        </p>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <div>
                                <span class="font-bold text-gray-900">$ {{ number_format($vehicle->price_per_day, 2) }}</span>
                                <span class="text-xs text-gray-400"> / día</span>
                            </div>
                            <span class="text-xs text-gray-500 font-medium group-hover:underline">Ver detalles</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('vehiculos.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[var(--primary)] text-[var(--primary-foreground)] rounded-[var(--radius)] text-sm font-medium hover:opacity-90 transition-opacity">
                Ver catálogo completo
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ===== CATEGORÍAS ===== --}}
@if ($categories->isNotEmpty())
<section class="py-16 bg-white relative z-[40] shadow-md">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Explorar</p>
            <h2 class="text-2xl font-bold text-gray-900">Buscar por categoría</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($categories as $cat)
                <a href="{{ route('vehiculos.index', ['category_id' => $cat->id]) }}"
                   class="group flex items-center justify-center p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm transition-all text-center">
                    <span class="font-semibold text-sm text-gray-900">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== MARCAS ===== --}}
@if ($makes->isNotEmpty())
<section class="py-16 bg-white relative z-[30] shadow-md">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Marcas</p>
            <h2 class="text-2xl font-bold text-gray-900">Buscar por marca</h2>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach ($makes as $make)
                <a href="{{ route('vehiculos.index', ['search' => $make]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm border border-gray-200 rounded-[var(--radius)] hover:border-gray-300 hover:bg-gray-50 transition-colors text-gray-700">
                    @if (!empty($brandLogos[$make]))
                        <img src="{{ $brandLogos[$make] }}"
                             alt="{{ $make }}"
                             class="w-5 h-5 object-contain shrink-0"
                             onerror="this.style.display='none'">
                    @endif
                    {{ $make }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== CÓMO FUNCIONA ===== --}}
<section class="bg-gray-900 py-16 relative z-[20] shadow-md">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Proceso</p>
            <h2 class="text-2xl font-bold text-white">Cómo funciona</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ([
                ['icon' => 'M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z', 'title' => 'Elige tu vehículo', 'desc' => 'Filtra por categoría, fechas y precio hasta encontrar el ideal para ti.'],
                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Reserva en línea', 'desc' => 'Selecciona las fechas, añade extras y confirma tu reserva en minutos.'],
                ['icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', 'title' => 'Recibe las llaves', 'desc' => 'Paga y recibe el comprobante de entrega con todos los detalles del vehículo.'],
            ] as $item)
                <div class="flex flex-col items-start p-5 rounded-xl bg-white/5 border border-white/10">
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-white mb-1">{{ $item['title'] }}</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== POR QUÉ ELEGIRNOS ===== --}}
<section class="bg-gray-900 py-16 relative z-[10] shadow-md">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Ventajas</p>
            <h2 class="text-2xl font-bold text-white">Por qué elegirnos</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Cobertura incluida', 'desc' => 'Todos los vehículos cuentan con seguro básico desde el primer día.'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Precios transparentes', 'desc' => 'Sin cargos ocultos. Ves el total con impuestos antes de confirmar tu reserva.'],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Cancelación flexible', 'desc' => 'Cancela sin cargo más de 48 horas antes del inicio del alquiler.'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'title' => 'Comprobante digital', 'desc' => 'Recibe un comprobante de entrega detallado con placa, kilometraje y combustible.'],
            ] as $item)
                <div class="flex flex-col items-start p-5 rounded-xl bg-white/5 border border-white/10">
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-sm text-white mb-1">{{ $item['title'] }}</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

</x-public-layout>
