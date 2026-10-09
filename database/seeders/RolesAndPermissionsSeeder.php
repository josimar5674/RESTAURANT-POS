<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permisos
        |--------------------------------------------------------------------------
        */

      $permissions = [

    // Usuarios
    'users.view',
    'users.create',
    'users.edit',
    'users.delete',

    // Roles
    'roles.view',
    'roles.create',
    'roles.edit',
    'roles.delete',

    // Productos
    'products.view',
    'products.create',
    'products.edit',
    'products.delete',

    // Categorías de productos
    'product_categories.view',
    'product_categories.create',
    'product_categories.edit',
    'product_categories.delete',

    // Grupos de modificadores
    'modifier_groups.view',
    'modifier_groups.create',
    'modifier_groups.edit',
    'modifier_groups.delete',

    // Opciones de modificadores
    'modifier_options.view',
    'modifier_options.create',
    'modifier_options.edit',
    'modifier_options.delete',

    // Impuestos
    'taxes.view',
    'taxes.create',
    'taxes.edit',
    'taxes.delete',

    // Mesas / configuración del salón
    'tables.view',
    'tables.create',
    'tables.edit',
    'tables.delete',
];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $adminRole = Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Gerente',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Cajero',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Mesero',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Cocina',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Administrador
        |--------------------------------------------------------------------------
        */

        // El administrador tiene todos los permisos
        $adminRole->syncPermissions($permissions);

        /*
        |--------------------------------------------------------------------------
        | Usuario administrador
        |--------------------------------------------------------------------------
        */

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

        // Limpiar nuevamente la caché
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}