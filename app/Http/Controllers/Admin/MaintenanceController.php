<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePeriod;
use App\Models\Resource;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenancePeriod::with(['resource', 'creator']);

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        if ($request->filled('upcoming')) {
            $query->where('start_date', '>=', now());
        }

        $maintenances = $query->latest()->paginate(15);
        $resources = Resource::all();

        return view('admin.maintenance.index', compact('maintenances', 'resources'));
    }

    public function create()
    {
        $resources = Resource::all();
        return view('admin.maintenance.create', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'description' => 'required|string',
        ]);

        $maintenance = MaintenancePeriod::create([
            'resource_id' => $validated['resource_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'],
            'created_by' => auth()->id(),
        ]);

        $resource = Resource::find($validated['resource_id']);
        $resource->update(['status' => 'en_maintenance']);

        $affectedReservations = Reservation::where('resource_id', $validated['resource_id'])
            ->where('status', 'approuvee')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                          ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->get();

        foreach ($affectedReservations as $reservation) {
            Notification::create([
                'user_id' => $reservation->user_id,
                'title' => 'Maintenance planifiée',
                'message' => "Une maintenance est planifiée pour {$resource->name} du " . 
                            date('d/m/Y', strtotime($validated['start_date'])) . " au " . 
                            date('d/m/Y', strtotime($validated['end_date'])) . 
                            ". Votre réservation pourrait être affectée.",
                'type' => 'warning',
            ]);
        }

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Période de maintenance créée avec succès!');
    }

    public function destroy(MaintenancePeriod $maintenance)
    {
        $resource = $maintenance->resource;
        
        $maintenance->delete();

        $hasOtherMaintenance = MaintenancePeriod::where('resource_id', $resource->id)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();

        if (!$hasOtherMaintenance) {
            $resource->update(['status' => 'disponible']);
        }

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Période de maintenance supprimée avec succès!');
    }
}
