<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Administrateur')->first();
        $managerRole = Role::where('name', 'Responsable technique')->first();
        $userRole = Role::where('name', 'Utilisateur interne')->first();
        $guestRole = Role::where('name', 'Invité')->first();

        // Administrateur
        User::create([
            'name' => 'Admin DataCenter',
            'email' => 'admin@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        // Responsables techniques
        User::create([
            'name' => 'Manager Serveurs',
            'email' => 'manager.serveurs@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $managerRole->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Manager Réseau',
            'email' => 'manager.reseau@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $managerRole->id,
            'is_active' => true,
        ]);

        // Utilisateurs internes
        User::create([
            'name' => 'Dr. Jean Dupont',
            'email' => 'jean.dupont@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Prof. Marie Martin',
            'email' => 'marie.martin@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ing. Pierre Bernard',
            'email' => 'pierre.bernard@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        // Invités
        User::create([
            'name' => 'Invité Test',
            'email' => 'invite@datacenter.com',
            'password' => Hash::make('password123'),
            'role_id' => $guestRole->id,
            'is_active' => true,
        ]);
    }
}
