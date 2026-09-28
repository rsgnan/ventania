<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', 'Ventania')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 font-sans text-slate-900">
    <div class="flex min-h-screen">
        <x-sidebar />

        <main class="flex-1 px-9 py-7">
            @yield('content')
        </main>
    </div>
</body>
</html>
