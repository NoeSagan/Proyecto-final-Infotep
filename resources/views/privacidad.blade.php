<x-public-layout>
    <x-slot:title>Política de Privacidad | AutoAlquiler</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-card>
            <x-card.body class="prose max-w-none">

                <h1 class="text-3xl font-bold mb-1">Política de Privacidad</h1>
                <p class="text-sm opacity-50 mb-6">Última actualización: {{ date('d/m/Y') }}</p>

                <section>
                    <h2>1. Información que recopilamos</h2>
                    <p>En AutoAlquiler recopilamos la siguiente información personal cuando creas una cuenta o realizas una reserva:</p>
                    <ul>
                        <li>Nombre completo y dirección de correo electrónico</li>
                        <li>Información de la reserva: fechas, vehículo seleccionado, cantidad de pasajeros</li>
                        <li>Datos del vehículo entregado: placa, kilometraje y nivel de combustible al momento de la entrega</li>
                    </ul>
                </section>

                <section>
                    <h2>2. Uso de la información</h2>
                    <p>La información recopilada se utiliza exclusivamente para:</p>
                    <ul>
                        <li>Gestionar y confirmar tus reservas de vehículos</li>
                        <li>Enviarte correos de confirmación de pago</li>
                        <li>Mejorar nuestros servicios y atención al cliente</li>
                        <li>Cumplir con obligaciones legales y contractuales</li>
                    </ul>
                </section>

                <section>
                    <h2>3. Protección de datos</h2>
                    <p>Tus datos se almacenan de forma segura en servidores con cifrado. Las contraseñas se almacenan en forma de hash irreversible (bcrypt). Nunca vendemos ni compartimos tu información personal con terceros, salvo cuando lo exija la ley.</p>
                </section>

                <section>
                    <h2>4. Cookies</h2>
                    <p>Utilizamos cookies de sesión estrictamente necesarias para el funcionamiento de la plataforma (autenticación y seguridad CSRF). No utilizamos cookies de rastreo publicitario.</p>
                </section>

                <section>
                    <h2>5. Tus derechos</h2>
                    <p>Tienes derecho a acceder, rectificar o eliminar tus datos personales. Para ejercer estos derechos, contáctanos a través de nuestra <a href="{{ route('contacto') }}">página de contacto</a>.</p>
                </section>

                <section>
                    <h2>6. Contacto</h2>
                    <p>Si tienes preguntas sobre esta política de privacidad, puedes contactarnos en <a href="{{ route('contacto') }}">el formulario de contacto</a>.</p>
                </section>

            </x-card.body>
        </x-card>
    </div>

</x-public-layout>
