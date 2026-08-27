<!DOCTYPE html>
<html lang="es" data-theme="newfrost">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-8xl font-bold text-base-content/20">404</h1>
        <h2 class="text-2xl font-semibold text-base-content mt-4">Página no encontrada</h2>
        <p class="text-base-content/50 mt-2">El vehículo, reserva o página que buscas no existe.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-6">
            Volver al inicio
        </a>
    </div>
</body>
</html>
