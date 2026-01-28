<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Serveurs',
                'description' => 'Serveurs physiques du Data Center',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Machines virtuelles',
                'description' => 'Instances de machines virtuelles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stockage',
                'description' => 'Baies de stockage et dispositifs de stockage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Équipements réseau',
                'description' => 'Routeurs, commutateurs, pare-feu, etc.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
