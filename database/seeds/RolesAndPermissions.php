<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Reset cached roles and permissions
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //  // create permissions
        //  Permission::create(['name' => 'edit user']);
        //  Permission::create(['name' => 'read users']);
        //  Permission::create(['name' => 'update user']);
        //  Permission::create(['name' => 'delete user']);
 
        //  // create permissions
        //  Permission::create(['name' => 'edit role']);
        //  Permission::create(['name' => 'read roles']);
        //  Permission::create(['name' => 'update role']);
        //  Permission::create(['name' => 'delete role']);
 
        //  // create permissions
        //  Permission::create(['name' => 'edit permission']);
        //  Permission::create(['name' => 'read permissions']);
        //  Permission::create(['name' => 'update permission']);
        //  Permission::create(['name' => 'delete permission']);
 
         // create roles and assign created permissions
 
         // this can be done as separate statements
        //  $role = Role::create(['name' => 'editor']);
        //  $role->givePermissionTo('update user');
 
        //  // or may be done by chaining
        //  $role = Role::create(['name' => 'moderador'])
        //      ->givePermissionTo([
        //          'edit user',
        //          'read users',
        //          'update user',
        //          'delete user'
        //      ]);
        Permission::create([
            'id' => 1 ,
            'name' => 'ver datanet'
            ]);
        Permission::create([
            'id' => 2 ,
            'name' => 'Modificar datanet'
            ]);        
        Permission::create([
            'id' => 3 ,
            'name' => 'Insertar datanet'
            ]);
        Permission::create([
            'id' => 4 ,
            'name' => 'Eliminar datanet'
            ]);    
        Permission::create([
            'id' => 5 ,
            'name' => 'Exportar datanet'
            ]);                
         $role = Role::create(['name' => 'super-admin']);
         $role->givePermissionTo(Permission::all());
     }
    
}
