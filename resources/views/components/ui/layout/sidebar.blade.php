<aside
    class="fixed left-0 top-0 h-screen w-72 bg-slate-900 text-slate-100 flex flex-col shadow-xl">

    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-slate-800">

        <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-xl">

            R

        </div>

        <div class="ml-4">

            <h1 class="font-bold text-lg">
                Restaurant POS
            </h1>

            <p class="text-xs text-slate-400">
                Administración
            </p>

        </div>

    </div>

    <!-- Menú -->
    <nav class="flex-1 py-6">

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Dashboard

        </a>

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Usuarios

        </a>

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Restaurante

        </a>

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Mesas

        </a>

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Cocina

        </a>

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Caja

        </a>

        <a href="#"
           class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition">

            Reportes

        </a>

    </nav>

    <!-- Usuario -->
    <div class="border-t border-slate-800 p-6">

        <div class="font-semibold">

            {{ auth()->user()->first_name }}

        </div>

        <div class="text-sm text-slate-400">

            {{ auth()->user()->email }}

        </div>

    </div>

</aside>