<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            #dashboard
            Permission::firstOrCreate(['group' => 'dashboard','name' => 'View Dashboard', 'is_active' => 1, 'type'=> 1, 'module'=>'default']);
            Permission::firstOrCreate(['group' => 'dashboard','name' => 'View Manger Count Statistics', 'is_active' => 1, 'type'=> 1, 'module'=>'default']);
            Permission::firstOrCreate(['group' => 'dashboard','name' => 'View Users Count Statistics', 'is_active' => 1, 'type'=> 1, 'module'=>'default']);
            Permission::firstOrCreate(['group' => 'dashboard','name' => 'View Product Count Statistics', 'is_active' => 1, 'type'=> 1, 'module'=>'default']);


            #profile
            Permission::firstOrCreate(['group'=> 'user profile', 'name' => 'View Profile', 'is_active'=>1, 'type'=> 1, 'module'=>'default']);
            Permission::firstOrCreate(['group'=> 'user profile', 'name' => 'Edit Profile', 'is_active'=>1, 'type'=> 1, 'module'=>'default']);
            Permission::firstOrCreate(['group'=> 'user profile', 'name' => 'Change Password', 'is_active'=>1, 'type'=> 1, 'module'=>'default']);

            #admin
            Permission::firstOrCreate(['group'=> 'admin', 'name' => 'View All Admin', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'admin', 'name' => 'Create Admin', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'admin', 'name' => 'View Admin Details', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'admin', 'name' => 'Edit Admin', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'admin', 'name' => 'Delete Admin', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);


            #User
            Permission::firstOrCreate(['group'=> 'user', 'name' => 'View All Users', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'user', 'name' => 'Create User', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'user', 'name' => 'View User Details', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'user', 'name' => 'Edit User', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'user', 'name' => 'Delete User', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);

            #Role
            Permission::firstOrCreate(['group'=> 'role', 'name' => 'View All Roles', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'role', 'name' => 'Create Role', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'role', 'name' => 'View Role Details', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);
            Permission::firstOrCreate(['group'=> 'role', 'name' => 'Edit Role', 'is_active'=>1, 'type'=> 1, 'module'=>'users']);


            #Product
            Permission::firstOrCreate(['group'=> 'product', 'name' => 'View All Products', 'is_active'=>1, 'type'=> 1, 'module'=>'list']);
            Permission::firstOrCreate(['group'=> 'product', 'name' => 'Create Product', 'is_active'=>1, 'type'=> 1, 'module'=>'list']);
            Permission::firstOrCreate(['group'=> 'product', 'name' => 'View Product Details', 'is_active'=>1, 'type'=> 1, 'module'=>'list']);
            Permission::firstOrCreate(['group'=> 'product', 'name' => 'Edit Product', 'is_active'=>1, 'type'=> 1, 'module'=>'list']);
            Permission::firstOrCreate(['group'=> 'product', 'name' => 'Delete Product', 'is_active'=>1, 'type'=> 1, 'module'=>'list']);

            #Store Attribute Products
            Permission::firstOrCreate(['group'=> 'attribute products', 'name' => 'View All Attribute Products', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'attribute products', 'name' => 'Create Attribute Product', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'attribute products', 'name' => 'View Attribute Product Details', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'attribute products', 'name' => 'Edit Attribute Product', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'attribute products', 'name' => 'Delete Attribute Product', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);


            #Store Settings LockupType
            Permission::firstOrCreate(['group'=> 'settings lockup_type', 'name' => 'View All LockupTypes', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup_type', 'name' => 'Create LockupType', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup_type', 'name' => 'View LockupType Details', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup_type', 'name' => 'Edit LockupType', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup_type', 'name' => 'Delete LockupType', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);

            #Store Settings Lockup
            Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'View All Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'Create Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'View Lockup Details', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'Edit Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'Delete Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);

            #Store Settings App Dashboard
            // Permission::firstOrCreate(['group'=> 'settings app dashboard', 'name' => 'App Dashboard', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            // Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'Create Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            // Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'View Lockup Details', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            // Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'Edit Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            // Permission::firstOrCreate(['group'=> 'settings lockup', 'name' => 'Delete Lockup', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);

            #Store Configuration
            Permission::firstOrCreate(['group'=> 'configuration base currency', 'name' => 'View Currency Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'configuration base currency', 'name' => 'Edit Currency Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'configuration payment method', 'name' => 'View Payment Method Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'configuration payment method', 'name' => 'Edit Payment Method Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);

            Permission::firstOrCreate(['group'=> 'configuration Shipping Charges', 'name' => 'View Shipping Charges Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'configuration Shipping Charges', 'name' => 'Edit Shipping Charges Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);

            Permission::firstOrCreate(['group'=> 'configuration Mobile Version', 'name' => 'View Mobile Version Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
            Permission::firstOrCreate(['group'=> 'configuration Mobile Version', 'name' => 'Edit Mobile Version Setting', 'is_active'=>1, 'type'=> 1, 'module'=>'store']);
    }
}
