<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdmin = Role::create([
            'name' => 'super admin',
            'guard_name' => 'admin',
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage categories',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage products',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage reviews',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage blogs',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage blog-categories',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage tags',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage comments',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage orders',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage members',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage statistics',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage testimonials',
            'guard_name' => 'admin'
        ]);
       
        $adminPermissions[] = Permission::create([
            'name' => 'manage dashboard',
            'guard_name' => 'admin',
        ]);
        $adminPermissions[] = Permission::create([
            'name' => 'manage role-permission',
            'guard_name' => 'admin'
        ]);

        $adminPermissions[] = Permission::create([
            'name' => 'manage setting',
            'guard_name' => 'admin'
        ]);

        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole(Role::findByName('super admin', 'admin'));

        $superAdmin->syncPermissions($adminPermissions);
    }
}
