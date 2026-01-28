<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Category;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $totalResources = Resource::count();
        $activeResources = Resource::where('is_active', true)->count();
        $availableResources = Resource::where('status', 'disponible')->where('is_active', true)->count();
        $maintenanceResources = Resource::where('status', 'en_maintenance')->where('is_active', true)->count();
        $unavailableResources = Resource::where('status', 'indisponible')->count();
        
        $occupiedResources = Reservation::where('status', 'approuvee')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->distinct('resource_id')
            ->count('resource_id');
        
        $occupationRate = $activeResources > 0 ? round(($occupiedResources / $activeResources) * 100, 2) : 0;

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_resources' => $totalResources,
            'active_resources' => $activeResources,
            'available_resources' => $availableResources,
            'maintenance_resources' => $maintenanceResources,
            'unavailable_resources' => $unavailableResources,
            'occupied_resources' => $occupiedResources,
            'occupation_rate' => $occupationRate,
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'en_attente')->count(),
            'approved_reservations' => Reservation::where('status', 'approuvee')->count(),
            'active_reservations' => Reservation::where('status', 'approuvee')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'total_incidents' => Incident::count(),
            'open_incidents' => Incident::whereIn('status', ['ouvert', 'en_cours'])->count(),
            'top_users' => User::withCount('reservations')
                ->orderBy('reservations_count', 'desc')
                ->take(10)
                ->get(),
            'top_resources' => Resource::withCount('reservations')
                ->orderBy('reservations_count', 'desc')
                ->take(10)
                ->get(),
            'recent_reservations' => Reservation::with(['user', 'resource'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(),
        ];

        $resourcesByCategory = Category::withCount('resources')->get();

        $reservationsByStatus = Reservation::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $reservationsByMonth = Reservation::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $incidentsByPriority = Incident::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        return view('admin.statistics.index', compact(
            'stats',
            'resourcesByCategory',
            'reservationsByStatus',
            'reservationsByMonth',
            'incidentsByPriority'
        ));
    }
}
