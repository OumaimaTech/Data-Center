<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with('category');

        // Filtres
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $resources = $query->paginate(12);
        $categories = Category::all();

        return view('resources.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('resources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,en_maintenance,indisponible',
        ]);

        Resource::create($validated);

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
        return view('resources.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:disponible,en_maintenance,indisponible',
        ]);

        $resource->update($validated);

        return redirect()->route('resources.index')
            ->with('success', 'Ressource mise à jour avec succès!');
    }

    public function destroy(Resource $resource)
    {
        // Vérifier s'il y a des réservations actives
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
