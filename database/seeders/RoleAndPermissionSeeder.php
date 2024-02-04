<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::insert([
            ['name' => 'categories.create', 'guard_name' => 'web'],
            ['name' => 'categories.read', 'guard_name' => 'web'],
            ['name' => 'categories.update', 'guard_name' => 'web'],
            ['name' => 'categories.delete', 'guard_name' => 'web'],

            ['name' => 'products.create', 'guard_name' => 'web'],
            ['name' => 'products.read', 'guard_name' => 'web'],
            ['name' => 'products.update', 'guard_name' => 'web'],
            ['name' => 'products.delete', 'guard_name' => 'web'],

            ['name' => 'toppings.create', 'guard_name' => 'web'],
            ['name' => 'toppings.read', 'guard_name' => 'web'],
            ['name' => 'toppings.update', 'guard_name' => 'web'],
            ['name' => 'toppings.delete', 'guard_name' => 'web'],

            ['name' => 'employees.create', 'guard_name' => 'web'],
            ['name' => 'employees.read', 'guard_name' => 'web'],
            ['name' => 'employees.update', 'guard_name' => 'web'],
            ['name' => 'employees.delete', 'guard_name' => 'web'],

            ['name' => 'order.create', 'guard_name' => 'web'],
            ['name' => 'order.read', 'guard_name' => 'web'],
            ['name' => 'order.update', 'guard_name' => 'web'],
            ['name' => 'order.delete', 'guard_name' => 'web'],

            ['name' => 'report.create', 'guard_name' => 'web'],
            ['name' => 'report.read', 'guard_name' => 'web'],
            ['name' => 'report.update', 'guard_name' => 'web'],
            ['name' => 'report.delete', 'guard_name' => 'web'],

            ['name' => 'kot.read', 'guard_name' => 'web'],
            ['name' => 'kot.update', 'guard_name' => 'web'],

            ['name' => 'dt.read', 'guard_name' => 'web'],
            ['name' => 'dt.update', 'guard_name' => 'web'],

            ['name' => 'inventory.create', 'guard_name' => 'web'],
            ['name' => 'inventory.read', 'guard_name' => 'web'],
            ['name' => 'inventory.update', 'guard_name' => 'web'],
            ['name' => 'inventory.delete', 'guard_name' => 'web'],

            ['name' => 'table.create', 'guard_name' => 'web'],
            ['name' => 'table.read', 'guard_name' => 'web'],
            ['name' => 'table.update', 'guard_name' => 'web'],
            ['name' => 'table.delete', 'guard_name' => 'web'],

        ]);

        $ownerRole = Role::create(['name' => 'owner']);
        $cashierRole = Role::create(['name' => 'cashier']);
        $chefRole = Role::create(['name' => 'chef']);
        $captainRole = Role::create(['name' => 'captain']);
        $deliveryRole = Role::create(['name' => 'delivery']);
        $storeKeeperRole = Role::create(['name' => 'storeKeeper']);

        $ownerRole->givePermissionTo([
            'categories.create',
            'categories.update',
            'categories.delete',
            'categories.read',

            'products.create',
            'products.update',
            'products.delete',
            'products.read',

            'toppings.create',
            'toppings.update',
            'toppings.delete',
            'toppings.read',

            'employees.create',
            'employees.update',
            'employees.delete',
            'employees.read',

            'order.create',
            'order.update',
            'order.delete',
            'order.read',

            'report.create',
            'report.update',
            'report.delete',
            'report.read',

            'kot.read',
            'kot.update',

            'dt.read',
            'dt.update',

            'inventory.create',
            'inventory.update',
            'inventory.delete',
            'inventory.read',

            'table.create',
            'table.update',
            'table.delete',
            'table.read',
        ]);

        $captainRole->givePermissionTo([
            'categories.read',
            'categories.create',
            'categories.update',

            'products.read',
            'products.create',
            'products.update',

            'order.create',
            'order.update',
            'order.delete',
            'order.read',

            'table.update',
            'table.read',
        ]);

        $cashierRole->givePermissionTo([
            'categories.read',

            'products.read',

            'order.create',
            'order.update',
            'order.delete',
            'order.read',
        ]);

        $chefRole->givePermissionTo([
            'kot.read',
            'kot.update',
            'table.read',
            'categories.read',
            'categories.create',
            'categories.update',

            'products.read',
            'products.create',
            'products.update',
        ]);

        $deliveryRole->givePermissionTo([
            'dt.read',
            'dt.update'
        ]);

        $storeKeeperRole->givePermissionTo([
            'dt.read',
            'dt.update'
        ]);
    }
}
