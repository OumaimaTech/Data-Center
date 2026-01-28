<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class FixApprovedAtDates extends Command
{
    protected $signature = 'reservations:fix-approved-dates';

    protected $description = 'Fix null approved_at dates for approved/rejected reservations';

    public function handle()
    {
        $this->info('Fixing approved_at dates for reservations...');

        $reservations = Reservation::whereIn('status', ['approuvee', 'refusee'])
            ->whereNotNull('approved_by')
            ->whereNull('approved_at')
            ->get();

        if ($reservations->isEmpty()) {
            $this->info('No reservations need fixing.');
            return 0;
        }

        $count = 0;
        foreach ($reservations as $reservation) {
            $reservation->approved_at = $reservation->updated_at;
            $reservation->save();
            $count++;
            
            $this->line("Fixed reservation #{$reservation->id}");
        }

        $this->info("Successfully fixed {$count} reservation(s).");
        return 0;
    }
}
