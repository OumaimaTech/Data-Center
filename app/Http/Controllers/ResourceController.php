<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Resource::with(['category', 'manager']);

        if ($user->role->name === 'Responsable technique') {
            $query->where('manager_id', $user->id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        $resources = $query->paginate(12);
        $categories = Category::all();
        $managers = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Responsable technique');
        })->get();

        return view('resources.index', compact('resources', 'categories', 'managers'));
    }

    public function create()
    {
        $categories = Category::all();
        $managers = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Responsable technique');
        })->get();
        return view('resources.create', compact('categories', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'spec_keys' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,en_maintenance,indisponible',
        ]);

        // Convert spec_keys and spec_values to specifications array
        $specifications = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->spec_keys;
            $values = $request->spec_values;
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specifications[$key] = $values[$index];
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'manager_id' => $validated['manager_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'specifications' => !empty($specifications) ? $specifications : null,
            'location' => $validated['location'] ?? null,
            'status' => $validated['status'],
        ];

        Resource::create($data);

        return redirect()->route('resources.index')
            ->with('success', 'Ressource créée avec succès!');
    }

    public function show(Resource $resource)
    {
        $resource->load(['category', 'reservations.user']);

        $upcomingReservations = $resource->reservations()
            ->where('status', 'approuvee')
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->get();

        return view('resources.show', compact('resource', 'upcomingReservations'));
    }

    public function edit(Resource $resource)
    {
        $categories = Category::all();
        $managers = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Responsable technique');
        })->get();
        return view('resources.edit', compact('resource', 'categories', 'managers'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'spec_keys' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,en_maintenance,indisponible',
        ]);

        // Convert spec_keys and spec_values to specifications array
        $specifications = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            $keys = $request->spec_keys;
            $values = $request->spec_values;
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && !empty($values[$index])) {
                    $specifications[$key] = $values[$index];
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'manager_id' => $validated['manager_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'specifications' => !empty($specifications) ? $specifications : null,
            'location' => $validated['location'] ?? null,
            'status' => $validated['status'],
        ];

        $resource->update($data);

        return redirect()->route('resources.index')
            ->with('success', 'Ressource mise à jour avec succès!');
    }

    public function toggleStatus(Resource $resource)
    {
        $resource->is_active = !$resource->is_active;
        $resource->save();

        $status = $resource->is_active ? 'activée' : 'désactivée';

        return redirect()->route('resources.index')
            ->with('success', "Ressource {$status} avec succès!");
    }

    public function destroy(Resource $resource)
    {
        $activeReservations = $resource->reservations()
            ->where('status', 'approuvee')
            ->where('end_date', '>=', now())
            ->count();

        if ($activeReservations > 0) {
            return redirect()->route('resources.index')
                ->with('error', 'Impossible de supprimer une ressource avec des réservations actives.');
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Ressource supprimée avec succès!');
    }
}
