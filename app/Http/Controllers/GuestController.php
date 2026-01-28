<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function resources(Request $request)
    {
        $query = Resource::with('category')
            ->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $resources = $query->paginate(12);
        $categories = Category::withCount('resources')->get();

        return view('guest.resources', compact('resources', 'categories'));
    }

    public function show(Resource $resource)
    {
        if (!$resource->is_active) {
            abort(404);
        }

        $resource->load('category');

        return view('guest.show', compact('resource'));
    }

    public function info()
    {
        return view('guest.info');
    }
}
