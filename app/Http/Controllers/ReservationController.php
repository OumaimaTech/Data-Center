<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Notification;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->role || !in_array($user->role->name, ['Utilisateur interne', 'Responsable technique', 'Administrateur'])) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = Reservation::with(['resource', 'user']);

        if ($user->role->name === 'Utilisateur interne') {
            $query->where('user_id', $user->id);
        } elseif ($user->role->name === 'Responsable technique') {
            $query->whereHas('resource', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        if ($request->filled('user_id') && in_array($user->role->name, ['Responsable technique', 'Administrateur'])) {
            $query->where('user_id', $request->user_id);
        }

        $reservations = $query->latest()->paginate(15)->withQueryString();
        $resources = Resource::with('category')->get();
        
        // Récupérer tous les utilisateurs pour le filtre (pour managers et admins)
        $users = [];
        if (in_array($user->role->name, ['Responsable technique', 'Administrateur'])) {
            $users = \App\Models\User::whereHas('role', function ($q) {
                $q->where('name', 'Utilisateur interne');
            })->orderBy('name')->get();
        }

        return view('reservations.index', compact('reservations', 'resources', 'users'));
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $query = $user->reservations()->with(['resource.category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $reservations = $query->latest()->paginate(15);
        $resources = Resource::all();

        return view('reservations.history', compact('reservations', 'resources'));
    }

    public function create()
    {
        $resources = Resource::with('category')
            ->where('status', 'disponible')
            ->get();
        return view('reservations.create', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'justification' => 'required|string',
        ]);

        $conflicts = Reservation::where('resource_id', $validated['resource_id'])
            ->where('status', '!=', 'refusee')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                          ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($conflicts) {
            return back()->withErrors(['resource_id' => 'Cette ressource est déjà réservée pour cette période.'])
                ->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'resource_id' => $validated['resource_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'justification' => $validated['justification'],
            'status' => 'en_attente',
        ]);

        $this->notifyManagers($reservation, 'Nouvelle demande de réservation');

        return redirect()->route('reservations.index')
            ->with('success', 'Demande de réservation créée avec succès!');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['resource.category', 'user', 'approver']);
        
        $user = auth()->user();
        if ($user->role->name === 'Utilisateur interne' && $reservation->user_id !== $user->id) {
            abort(403);
        }

        return view('reservations.show', compact('reservation'));
    }

    public function approve(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'approuvee',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Notification::create([
            'user_id' => $reservation->user_id,
            'title' => 'Réservation approuvée',
            'message' => "Votre réservation pour {$reservation->resource->name} a été approuvée.",
            'type' => 'success',
        ]);

        return redirect()->back()->with('success', 'Réservation approuvée avec succès!');
    }

    public function reject(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $reservation->update([
            'status' => 'refusee',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        Notification::create([
            'user_id' => $reservation->user_id,
            'title' => 'Réservation refusée',
            'message' => "Votre réservation pour {$reservation->resource->name} a été refusée. Raison: {$validated['rejection_reason']}",
            'type' => 'error',
        ]);

        return redirect()->back()->with('success', 'Réservation refusée.');
    }

    public function destroy(Reservation $reservation)
    {
        $user = auth()->user();
        if ($reservation->user_id !== $user->id && $user->role->name !== 'Administrateur') {
            abort(403);
        }

        if ($reservation->status === 'approuvee' && $reservation->start_date <= now() && $reservation->end_date >= now()) {
            return back()->with('error', 'Impossible de supprimer une réservation active.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation supprimée avec succès!');
    }

    private function notifyManagers(Reservation $reservation, string $message)
    {
        $managers = \App\Models\User::whereHas('role', function ($query) {
            $query->whereIn('name', ['Responsable technique', 'Administrateur']);
        })->get();

        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => $message,
                'message' => "Nouvelle demande de réservation pour {$reservation->resource->name} par {$reservation->user->name}",
                'type' => 'info',
            ]);
        }
    }
}
