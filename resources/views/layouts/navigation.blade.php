{{-- HEADER --}}
<x-header sticky class="h-20 shadow-md">

    {{-- Mobile: sidebar toggle --}}
    <button class="lg:hidden -ml-1 shrink-0 size-9 inline-flex items-center justify-center rounded-[var(--radius)] bg-[var(--primary)] text-[var(--primary-foreground)] hover:opacity-90 transition-opacity"
            x-data @click="$dispatch('sidebar:toggle')"
            aria-label="Abrir menú">
        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Logo --}}
    <a href="{{ route('inicio') }}"
       class="limelight-regular font-bold text-lg text-[var(--foreground)] shrink-0">
        AutoAlquiler
    </a>

    {{-- Desktop nav links --}}
    <nav class="max-lg:hidden flex items-center gap-1 ml-6">
        @php
            $navLinks = (auth()->check() && auth()->user()->isAdmin())
                ? [
                    ['label' => 'Dashboard',  'href' => route('admin.dashboard'),        'match' => 'admin.dashboard'],
                    ['label' => 'Reservas',   'href' => route('admin.reservas.index'),   'match' => 'admin.reservas.*'],
                    ['label' => 'Vehículos',  'href' => route('admin.vehiculos.index'),  'match' => 'admin.vehiculos.*'],
                    ['label' => 'Usuarios',   'href' => route('admin.usuarios.index'),   'match' => 'admin.usuarios.*'],
                    ['label' => 'Categorías', 'href' => route('admin.categorias.index'), 'match' => 'admin.categorias.*'],
                    ['label' => 'Extras',     'href' => route('admin.extras.index'),     'match' => 'admin.extras.*'],
                    ['label' => 'Reportes',   'href' => route('admin.reportes.index'),   'match' => 'admin.reportes.*'],
                ]
                : [
                    ['label' => 'Catálogo',     'href' => route('vehiculos.index'),    'match' => 'vehiculos.*'],
                    ['label' => 'Mis Reservas', 'href' => route('mis-reservas.index'), 'match' => 'mis-reservas.*'],
                    ['label' => 'Mis Listas',   'href' => route('favoritos.index'),    'match' => 'favoritos.*'],
                ];
        @endphp
        @foreach ($navLinks as $link)
            <a href="{{ $link['href'] }}"
               class="px-3 py-1.5 text-sm rounded-[var(--radius-inner)] transition-colors
                      {{ request()->routeIs($link['match']) ? 'bg-[var(--muted)] font-medium' : 'opacity-70 hover:opacity-100 hover:bg-[var(--muted)]' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- Spacer --}}
    <div class="flex-1"></div>

    {{-- Auth section --}}
    @auth
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex items-center gap-2 h-9 px-2 rounded-[var(--radius)] text-sm hover:bg-[var(--muted)] transition-colors">
                <div class="size-8 rounded-full bg-[var(--primary)] text-[var(--primary-foreground)] flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="hidden sm:block font-medium max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                <svg class="size-4 opacity-50 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                 class="absolute right-0 top-full mt-2 w-52 bg-[var(--card)] border border-[var(--border)] rounded-[var(--radius)] shadow-md py-1 z-10">
                <div class="px-4 py-2.5 border-b border-[var(--border)]">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs opacity-50 truncate">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('perfil.edit') }}"
                   class="flex items-center px-4 py-2 text-sm hover:bg-[var(--muted)] transition-colors">
                    Mi Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-[var(--danger)] hover:bg-[var(--muted)] transition-colors">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="flex items-center gap-2">
            <x-btn href="{{ route('login') }}" style="outline" size="sm">Iniciar sesión</x-btn>
            <x-btn href="{{ route('register') }}" size="sm">Registrarse</x-btn>
        </div>
    @endauth

</x-header>

{{-- SIDEBAR (mobile only) --}}
<x-sidebar class="lg:hidden" sticky>

    <x-sidebar-toggle aria-label="Cerrar menú">
        <x-heroicon-o-x-mark class="size-5 text-[var(--muted-foreground)]" />
    </x-sidebar-toggle>

    <x-accordion :exclusive="true">
        @auth
            @if(auth()->user()->isAdmin())
                <x-accordion.item :expanded="true">
                    <x-accordion.title>Panel Admin</x-accordion.title>
                    <x-accordion.content>
                        @foreach ([
                            ['route' => 'admin.dashboard',        'label' => 'Dashboard',  'match' => 'admin.dashboard'],
                            ['route' => 'admin.reservas.index',   'label' => 'Reservas',   'match' => 'admin.reservas.*'],
                            ['route' => 'admin.vehiculos.index',  'label' => 'Vehículos',  'match' => 'admin.vehiculos.*'],
                            ['route' => 'admin.usuarios.index',   'label' => 'Usuarios',   'match' => 'admin.usuarios.*'],
                            ['route' => 'admin.categorias.index', 'label' => 'Categorías', 'match' => 'admin.categorias.*'],
                            ['route' => 'admin.extras.index',     'label' => 'Extras',     'match' => 'admin.extras.*'],
                            ['route' => 'admin.reportes.index',   'label' => 'Reportes',   'match' => 'admin.reportes.*'],
                        ] as $item)
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center px-3 py-2 text-sm rounded-[var(--radius-inner)] transition-colors
                                      {{ request()->routeIs($item['match']) ? 'bg-[var(--muted)] font-medium' : 'hover:bg-[var(--muted)]' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </x-accordion.content>
                </x-accordion.item>
            @else
                <x-accordion.item :expanded="true">
                    <x-accordion.title>Navegación</x-accordion.title>
                    <x-accordion.content>
                        @foreach ([
                            ['route' => 'vehiculos.index',    'label' => 'Catálogo',     'match' => 'vehiculos.*'],
                            ['route' => 'mis-reservas.index', 'label' => 'Mis Reservas', 'match' => 'mis-reservas.*'],
                            ['route' => 'favoritos.index',    'label' => 'Mis Listas',   'match' => 'favoritos.*'],
                        ] as $item)
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center px-3 py-2 text-sm rounded-[var(--radius-inner)] transition-colors
                                      {{ request()->routeIs($item['match']) ? 'bg-[var(--muted)] font-medium' : 'hover:bg-[var(--muted)]' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </x-accordion.content>
                </x-accordion.item>
            @endif
        @else
            <x-accordion.item :expanded="true">
                <x-accordion.title>Navegación</x-accordion.title>
                <x-accordion.content>
                    @foreach ([
                        ['route' => 'vehiculos.index',    'label' => 'Catálogo',     'match' => 'vehiculos.*'],
                        ['route' => 'mis-reservas.index', 'label' => 'Mis Reservas', 'match' => 'mis-reservas.*'],
                        ['route' => 'favoritos.index',    'label' => 'Mis Listas',   'match' => 'favoritos.*'],
                    ] as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center px-3 py-2 text-sm rounded-[var(--radius-inner)] transition-colors
                                  {{ request()->routeIs($item['match']) ? 'bg-[var(--muted)] font-medium' : 'hover:bg-[var(--muted)]' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </x-accordion.content>
            </x-accordion.item>
        @endauth

        <x-accordion.item>
            <x-accordion.title>Legal</x-accordion.title>
            <x-accordion.content>
                @foreach ([
                    ['route' => 'terminos',   'label' => 'Términos y condiciones', 'match' => 'terminos'],
                    ['route' => 'privacidad', 'label' => 'Política de privacidad', 'match' => 'privacidad'],
                    ['route' => 'contacto',   'label' => 'Contacto',               'match' => 'contacto'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center px-3 py-2 text-sm rounded-[var(--radius-inner)] transition-colors
                              {{ request()->routeIs($item['match']) ? 'bg-[var(--muted)] font-medium' : 'hover:bg-[var(--muted)]' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </x-accordion.content>
        </x-accordion.item>
    </x-accordion>

</x-sidebar>
