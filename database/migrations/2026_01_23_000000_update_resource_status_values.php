<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les valeurs de statut existantes de l'anglais vers le français
        DB::table('resources')->where('status', 'available')->update(['status' => 'disponible']);
        DB::table('resources')->where('status', 'reserved')->update(['status' => 'reserve']);
        DB::table('resources')->where('status', 'maintenance')->update(['status' => 'en_maintenance']);
        DB::table('resources')->where('status', 'disabled')->update(['status' => 'indisponible']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux valeurs en anglais si nécessaire
        DB::table('resources')->where('status', 'disponible')->update(['status' => 'available']);
        DB::table('resources')->where('status', 'reserve')->update(['status' => 'reserved']);
        DB::table('resources')->where('status', 'en_maintenance')->update(['status' => 'maintenance']);
        DB::table('resources')->where('status', 'indisponible')->update(['status' => 'disabled']);
    }
};
