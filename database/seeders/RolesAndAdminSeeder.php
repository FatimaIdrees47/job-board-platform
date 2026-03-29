<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'employer', 'candidate'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@techjobs.pk'],
            [
                'name'              => 'Platform Admin',
                'password'          => Hash::make('Admin@1234'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('Roles created: admin, employer, candidate');
        $this->command->info('Admin seeded: admin@techjobs.pk / Admin@1234');
    }
}