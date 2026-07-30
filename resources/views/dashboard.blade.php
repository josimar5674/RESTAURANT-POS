@extends('layouts.app')

@section('title', 'Dashboard')

@section('subtitle', 'Resumen general del restaurante')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <x-ui.cards.stat
        title="Ventas Hoy"
        value="L 0.00"
        description="Sin ventas registradas">

        💰

    </x-ui.cards.stat>

    <x-ui.cards.stat
        title="Mesas Ocupadas"
        value="0"
        description="Todas disponibles">

        🍽️

    </x-ui.cards.stat>

    <x-ui.cards.stat
        title="Órdenes Activas"
        value="0"
        description="No hay órdenes">

        📋

    </x-ui.cards.stat>

    <x-ui.cards.stat
        title="Clientes"
        value="0"
        description="Sin clientes registrados">

        👥

    </x-ui.cards.stat>

</div>

@endsection