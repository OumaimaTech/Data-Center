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
        $query = Reservation::with(['resource', 'user']);

        // Filtrer selon le rôle
        if ($user->role->name === 'Utilisateur interne') {
            $query->where('user_id', $user->id);
        }

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        $reservations = $query->latest()->paginate(15);
        $resources = Resource::with('category')->get();

        return view('reservations.index', compact('reservations', 'resources'));
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
        
        // Vérifier les permissions
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

        // Notifier l'utilisateur
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
