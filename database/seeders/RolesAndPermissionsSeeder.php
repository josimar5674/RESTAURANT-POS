<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpia la caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Gerente']);
        Role::firstOrCreate(['name' => 'Cajero']);
        Role::firstOrCreate(['name' => 'Mesero']);
        Role::firstOrCreate(['name' => 'Cocina']);

        // Crear usuario administrador
$admin = User::firstOrCreate(
    ['email' => 'admin@restaurant.local'],
    [
        'uuid' => (string) Str::uuid(),
        'first_name' => 'Administrador',
        'last_name' => 'General',
        'phone' => null,
        'password' => Hash::make('Admin12345'),
        'active' => true,
        'locale' => 'es',
        'theme' => 'system',
    ]
);

        $admin->assignRole($adminRole);
    }
}