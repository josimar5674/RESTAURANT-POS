<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-slate-100">

    <x-ui.layout.sidebar />

    <main class="ml-72 min-h-screen">

        <header
            class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">

                    @yield('title')

                </h1>

                <p class="text-sm text-slate-500">

                    @yield('subtitle')

                </p>

            </div>

            <div>

                {{ now()->format('d/m/Y') }}

            </div>

        </header>

        <section class="p-8">

            @yield('content')

        </section>

    </main>

</body>

</html>