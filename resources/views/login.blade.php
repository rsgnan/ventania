<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login - Ventania</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-sans">
    <main class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-sm">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Ventania</h1>
                <p class="mt-2 text-sm text-gray-500">Gestão de estoque e vendas</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div>
                    <label for="username" class="mb-2 block text-sm font-medium text-gray-700"> Usuário </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    />
                </div>

                <div class="mt-5">
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-700"> Senha </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    />

                    <div class="mt-5 flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />

                        <label for="remember" class="ml-2 text-sm text-gray-600"> Lembrar-me </label>
                    </div>

                    <button
                        type="submit"
                        class="mt-6 w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
