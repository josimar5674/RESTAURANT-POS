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
<nav class="flex-1 py-6 overflow-y-auto">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center px-6 py-3 transition
       {{ request()->routeIs('dashboard')
            ? 'bg-slate-800 text-white border-l-4 border-blue-500'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        Dashboard
    </a>

    {{-- Operación --}}
    <div class="px-6 pt-6 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
        Operación
    </div>

    <a href="{{ route('restaurant.editor') }}"
       class="flex items-center px-6 py-3 transition
       {{ request()->routeIs('restaurant.editor')
            ? 'bg-slate-800 text-white border-l-4 border-blue-500'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        Restaurante y mesas
    </a>

    <a href="{{ route('products.index') }}"
       class="flex items-center px-6 py-3 transition
       {{ request()->routeIs('products.*')
            ? 'bg-slate-800 text-white border-l-4 border-blue-500'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        Productos
    </a>

    <a href="{{ route('product-categories.index') }}"
       class="flex items-center px-6 py-3 transition
       {{ request()->routeIs('product-categories.*')
            ? 'bg-slate-800 text-white border-l-4 border-blue-500'
            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        Categorías
    </a>

    {{-- Configuración --}}
    <div class="px-4 pt-6">

        <button
            type="button"
            id="configuration-menu-button"
            aria-controls="configuration-menu"
            aria-expanded="{{ request()->routeIs('taxes.*', 'modifier-groups.*', 'users.*', 'roles.*', 'payment-methods.*') ? 'true' : 'false' }}"
            class="w-full flex items-center justify-between px-2 py-3
                   text-xs font-semibold uppercase tracking-wider
                   text-slate-400 hover:text-white transition">

            <span>Configuración</span>

            <svg
                id="configuration-menu-arrow"
                class="w-4 h-4 transition-transform
                {{ request()->routeIs('taxes.*', 'modifier-groups.*', 'users.*', 'roles.*', 'payment-methods.*') ? 'rotate-180' : '' }}"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7" />

            </svg>

        </button>

        <div
            id="configuration-menu"
            class="space-y-1 {{ request()->routeIs('taxes.*', 'modifier-groups.*', 'users.*', 'roles.*', 'payment-methods.*') ? '' : 'hidden' }}">

            <a href="{{ route('taxes.index') }}"
               class="block rounded-lg px-6 py-3 text-sm transition
               {{ request()->routeIs('taxes.*')
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Impuestos
            </a>

            <a href="{{ route('modifier-groups.index') }}"
               class="block rounded-lg px-6 py-3 text-sm transition
               {{ request()->routeIs('modifier-groups.*')
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Grupos de modificadores
            </a>

            <a href="{{ route('users.index') }}"
               class="block rounded-lg px-6 py-3 text-sm transition
               {{ request()->routeIs('users.*')
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Usuarios
            </a>

            <a href="{{ route('roles.index') }}"
               class="block rounded-lg px-6 py-3 text-sm transition
               {{ request()->routeIs('roles.*')
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Roles y permisos
            </a>

            {{-- Se mostrará cuando creemos sus rutas --}}
            @if (Route::has('payment-methods.index'))
                <a href="{{ route('payment-methods.index') }}"
                   class="block rounded-lg px-6 py-3 text-sm transition
                   {{ request()->routeIs('payment-methods.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    Métodos de pago
                </a>
            @endif

        </div>

    </div>

</nav>



    <!-- Usuario -->
 <!-- Usuario -->
<div class="border-t border-slate-800 p-6">

    <div class="font-semibold">
        {{ auth()->user()?->first_name }}
    </div>

    <div class="text-sm text-slate-400 mb-4">
        {{ auth()->user()?->email }}
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button
            type="submit"
            class="w-full text-left px-4 py-2 rounded-lg
                   text-slate-300 hover:bg-slate-800
                   hover:text-white transition"
        >
            Cerrar sesión
        </button>

    </form>

</div>

</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('configuration-menu-button');
        const menu = document.getElementById('configuration-menu');
        const arrow = document.getElementById('configuration-menu-arrow');

        if (!button || !menu || !arrow) {
            return;
        }

        button.addEventListener('click', function () {
            const isHidden = menu.classList.toggle('hidden');

            arrow.classList.toggle('rotate-180', !isHidden);
            button.setAttribute('aria-expanded', String(!isHidden));
        });
    });
</script>