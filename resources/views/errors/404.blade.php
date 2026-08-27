<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-8xl font-bold text-gray-200">404</h1>
        <h2 class="text-2xl font-semibold text-gray-900 mt-4">Página no encontrada</h2>
        <p class="text-gray-500 mt-2">El vehículo, reserva o página que buscas no existe.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-6">
            Volver al inicio
        </a>
    </div>
</body>
</html>
