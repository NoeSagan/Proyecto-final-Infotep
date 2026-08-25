<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-8xl font-bold text-gray-300">404</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mt-4">Página no encontrada</h2>
        <p class="text-gray-500 mt-2">El vehículo, reserva o página que buscas no existe.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            Volver al inicio
        </a>
    </div>
</body>
</html>
