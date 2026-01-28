<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
        
        // Mettre à jour les valeurs existantes
        DB::table('reservations')->where('status', 'pending')->update(['status' => 'en_attente']);
        DB::table('reservations')->where('status', 'approved')->update(['status' => 'approuvee']);
        DB::table('reservations')->where('status', 'rejected')->update(['status' => 'refusee']);
        DB::table('reservations')->where('status', 'active')->update(['status' => 'active']);
        DB::table('reservations')->where('status', 'completed')->update(['status' => 'terminee']);
        DB::table('reservations')->where('status', 'cancelled')->update(['status' => 'annulee']);
    }

    public function down(): void
    {
        // Revenir aux valeurs anglaises
        DB::table('reservations')->where('status', 'en_attente')->update(['status' => 'pending']);
        DB::table('reservations')->where('status', 'approuvee')->update(['status' => 'approved']);
        DB::table('reservations')->where('status', 'refusee')->update(['status' => 'rejected']);
        DB::table('reservations')->where('status', 'terminee')->update(['status' => 'completed']);
        DB::table('reservations')->where('status', 'annulee')->update(['status' => 'cancelled']);
        
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};
