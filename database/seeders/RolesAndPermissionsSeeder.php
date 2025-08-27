<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $petugasRole = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $rektorRole = Role::firstOrCreate(['name' => 'rektor', 'guard_name' => 'web']);
        $htlRole = Role::firstOrCreate(['name' => 'htl', 'guard_name' => 'web']);

        $adminUser = User::firstOrCreate(
            ['email' => 'yovan.119140131@student.itera.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
            $this->command->info('Role "admin" assigned to yovan.119140131@student.itera.ac.id');
        } else {
            $this->command->info('yovan.119140131@student.itera.ac.id already has "admin" role.');
        }
    }
}
