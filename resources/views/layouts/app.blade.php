<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', 'Ventania')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-neutral-100 font-sans text-neutral-900">
    <x-sidebar />

    <main class="ml-60 min-h-screen px-9 py-7">
        <div class="w-full max-w-5xl">
            @yield('content')
        </div>
    </main>
</body>
</html>
