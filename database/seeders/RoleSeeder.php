<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'Invité',
                'description' => 'Peut consulter les ressources disponibles en mode lecture seule',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Utilisateur interne',
                'description' => 'Ingénieur / Enseignant / Doctorant - Peut faire des demandes de réservation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Responsable technique',
                'description' => 'Gestionnaire chargé d\'un ensemble de ressources du Data Center',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Administrateur',
                'description' => 'Gestion complète des utilisateurs, rôles et ressources',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
