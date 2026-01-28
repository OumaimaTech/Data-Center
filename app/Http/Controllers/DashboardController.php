<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roleName = $user->role->name;

        $totalResources = Resource::where('status', 'disponible')->count();
        $categories = Category::withCount('resources')->get();

        if ($roleName === 'Administrateur') {
            return $this->adminDashboard();
        } elseif ($roleName === 'Responsable technique') {
            return $this->managerDashboard();
        } elseif ($roleName === 'Utilisateur interne') {
            return $this->userDashboard();
        } else {
            return $this->guestDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_resources' => Resource::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'en_attente')->count(),
            'active_reservations' => Reservation::where('status', 'approuvee')->count(),
        ];

        $recentReservations = Reservation::with(['user', 'resource.category'])
            ->latest()
            ->take(10)
            ->get();

        $resourcesByCategory = Category::withCount('resources')->get();

        $occupationRate = $this->calculateOccupationRate();

        return view('dashboard.admin', compact('stats', 'recentReservations', 'resourcesByCategory', 'occupationRate'));
    }

    private function managerDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'my_resources' => Resource::count(),
            'pending_requests' => Reservation::where('status', 'en_attente')->count(),
            'active_reservations' => Reservation::where('status', 'approuvee')->count(),
        ];

        $pendingReservations = Reservation::with(['user', 'resource.category'])
            ->where('status', 'en_attente')
            ->latest()
            ->get();

        return view('dashboard.manager', compact('stats', 'pendingReservations'));
    }

    private function userDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'my_reservations' => $user->reservations()->count(),
            'pending' => $user->reservations()->where('status', 'en_attente')->count(),
            'approved' => $user->reservations()->where('status', 'approuvee')->count(),
            'active' => $user->reservations()->where('status', 'approuvee')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
        ];

        $myReservations = $user->reservations()
            ->with('resource.category')
            ->latest()
            ->take(10)
            ->get();

        $availableResources = Resource::where('status', 'disponible')->count();

        return view('dashboard.user', compact('stats', 'myReservations', 'availableResources'));
    }

    private function guestDashboard()
    {
        $availableResources = Resource::where('status', 'disponible')
            ->with('category')
            ->get();

        $categories = Category::withCount('resources')->get();

        return view('dashboard.guest', compact('availableResources', 'categories'));
    }

    private function calculateOccupationRate()
    {
        $totalResources = Resource::count();
        if ($totalResources === 0) {
            return 0;
        }

        $occupiedResources = Reservation::where('status', 'approuvee')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->distinct('resource_id')
            ->count('resource_id');

        return round(($occupiedResources / $totalResources) * 100, 2);
    }
}
