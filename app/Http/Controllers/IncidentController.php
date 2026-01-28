<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Resource;
use App\Models\Notification;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Incident::with(['user', 'resource', 'resolver']);

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

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        $incidents = $query->latest()->paginate(15);
        $resources = Resource::all();

        return view('incidents.index', compact('incidents', 'resources'));
    }

    public function create()
    {
        $resources = Resource::where('is_active', true)->get();
        return view('incidents.create', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:basse,moyenne,haute,critique',
        ]);

        $incident = Incident::create([
            'user_id' => auth()->id(),
            'resource_id' => $validated['resource_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'ouvert',
        ]);

        $resource = Resource::find($validated['resource_id']);
        if ($resource->manager_id) {
            Notification::create([
                'user_id' => $resource->manager_id,
                'title' => 'Nouvel incident signalé',
                'message' => "Un incident a été signalé pour {$resource->name}: {$validated['title']}",
                'type' => 'warning',
            ]);
        }

        $admins = \App\Models\User::whereHas('role', function ($query) {
            $query->where('name', 'Administrateur');
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Nouvel incident signalé',
                'message' => "Un incident a été signalé pour {$resource->name}: {$validated['title']}",
                'type' => 'warning',
            ]);
        }

        return redirect()->route('incidents.index')
            ->with('success', 'Incident signalé avec succès!');
    }

    public function show(Incident $incident)
    {
        $incident->load(['user', 'resource', 'resolver']);
        
        $user = auth()->user();
        if ($user->role->name === 'Utilisateur interne' && $incident->user_id !== $user->id) {
            abort(403);
        }

        if ($user->role->name === 'Responsable technique' && $incident->resource->manager_id !== $user->id) {
            abort(403);
        }

        return view('incidents.show', compact('incident'));
    }

    public function updateStatus(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'status' => 'required|in:ouvert,en_cours,resolu,ferme',
        ]);

        $incident->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Statut de l\'incident mis à jour!');
    }

    public function resolve(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $incident->update([
            'status' => 'resolu',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'resolution_notes' => $validated['resolution_notes'],
        ]);

        Notification::create([
            'user_id' => $incident->user_id,
            'title' => 'Incident résolu',
            'message' => "Votre incident '{$incident->title}' a été résolu.",
            'type' => 'success',
        ]);

        return redirect()->back()->with('success', 'Incident marqué comme résolu!');
    }

    public function destroy(Incident $incident)
    {
        $user = auth()->user();
        
        if ($user->role->name !== 'Administrateur' && $incident->user_id !== $user->id) {
            abort(403);
        }

        $incident->delete();

        return redirect()->route('incidents.index')
            ->with('success', 'Incident supprimé avec succès!');
    }
}
