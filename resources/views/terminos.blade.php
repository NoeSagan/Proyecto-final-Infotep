<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Términos y Condiciones</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-card>
                <x-card.body class="prose max-w-none">

                    <h2>1. Requisitos para alquilar</h2>
                    <p>El cliente debe tener como mínimo 21 años y presentar licencia de conducir vigente. Se requiere una tarjeta de crédito o débito válida al momento de la entrega del vehículo.</p>

                    <h2>2. Reservas y pagos</h2>
                    <p>Las reservas quedan en estado <strong>Pendiente</strong> hasta que el cliente confirme el pago. Una reserva pendiente no garantiza la disponibilidad indefinida del vehículo. El pago total se calcula como los días de alquiler multiplicados por el precio diario, más cualquier extra seleccionado.</p>

                    <h2>3. Entrega y devolución</h2>
                    <p>Al confirmar el pago se registran automáticamente la placa, el kilometraje actual y el nivel de combustible del vehículo. El cliente deberá devolverlo con el mismo nivel de combustible registrado al momento de la entrega. Diferencias en el kilometraje fuera de lo pactado podrán generar cargos adicionales.</p>

                    <h2>4. Cancelaciones</h2>
                    <p>Las reservas pueden cancelarse desde el panel "Mis Reservas" mientras estén en estado <strong>Pendiente</strong>. Las reservas en estado <strong>Confirmada</strong> deben cancelarse con al menos 24 horas de anticipación comunicándose con el servicio al cliente.</p>

                    <h2>5. Responsabilidad</h2>
                    <p>El cliente es responsable de cualquier daño al vehículo que ocurra durante el período de alquiler. Se recomienda contratar el seguro de ocupantes disponible en los extras al momento de la reserva.</p>

                    <h2>6. Modificaciones</h2>
                    <p>AutoAlquiler se reserva el derecho de modificar estos términos en cualquier momento. Los cambios serán publicados en esta página y aplicarán a las reservas creadas a partir de la fecha de publicación.</p>

                    <p class="text-sm opacity-50 mt-8">Última actualización: {{ date('d/m/Y') }}</p>
                </x-card.body>
            </x-card>
        </div>
    </div>
</x-app-layout>
