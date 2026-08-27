<footer class="bg-white border-t border-gray-200">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row justify-center gap-6 sm:gap-10 mb-4">
            <div>
                <p class="font-semibold text-sm text-gray-900 mb-2">Navegación</p>
                <ul class="space-y-1.5 text-sm text-gray-500">
                    <li><a href="{{ route('vehiculos.index') }}" class="hover:text-gray-900 transition-colors">Catálogo de vehículos</a></li>
                    @auth
                        <li><a href="{{ route('mis-reservas.index') }}" class="hover:text-gray-900 transition-colors">Mis Reservas</a></li>
                        <li><a href="{{ route('favoritos.index') }}"    class="hover:text-gray-900 transition-colors">Mis Listas</a></li>
                    @else
                        <li><a href="{{ route('login') }}"    class="hover:text-gray-900 transition-colors">Iniciar sesión</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-gray-900 transition-colors">Registrarse</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <p class="font-semibold text-sm text-gray-900 mb-2">Legal</p>
                <ul class="space-y-1.5 text-sm text-gray-500">
                    <li><a href="{{ route('terminos') }}"   class="hover:text-gray-900 transition-colors">Términos y condiciones</a></li>
                    <li><a href="{{ route('privacidad') }}" class="hover:text-gray-900 transition-colors">Política de privacidad</a></li>
                    <li><a href="{{ route('contacto') }}"   class="hover:text-gray-900 transition-colors">Contacto</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
