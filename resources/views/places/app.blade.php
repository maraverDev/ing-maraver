<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard acústico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">Acoustic Dashboard</span>
        <span class="text-white">{{ auth()->user()->name }}</span>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

</body>

</html>