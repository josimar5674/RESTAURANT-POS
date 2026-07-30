@extends('layouts.guest')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-center mb-2">
            Restaurant POS
        </h1>

        <p class="text-center text-gray-500 mb-8">
            Iniciar sesión
        </p>

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-700 p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">

            @csrf

            <div class="mb-4">

                <label class="block mb-2">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg p-3"
                    autofocus>

            </div>

            <div class="mb-6">

                <label class="block mb-2">
                    Contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg p-3">

            </div>

            <button
                class="w-full bg-blue-600 text-white rounded-lg p-3 hover:bg-blue-700">

                Iniciar sesión

            </button>

        </form>

    </div>

</div>

@endsection