@extends('layouts.app')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="page-header-actions">
    <div>
        <h1>Gestion des Catégories</h1>
        <p>Gérez les catégories de ressources du Data Center</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        + Nouvelle catégorie
    </a>
</div>

@if($categories->count() > 0)
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nombre de ressources</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>#{{ $category->id }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>{{ $category->resources_count }} ressource(s)</td>
                        <td>{{ $category->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline">
                                    Modifier
                                </a>
                                @if($category->resources_count == 0)
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                            Supprimer
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-danger" disabled title="Impossible de supprimer une catégorie avec des ressources">
                                        Supprimer
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
@else
    <div class="card text-center">
        <p>Aucune catégorie créée.</p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">
            Créer la première catégorie
        </a>
    </div>
@endif
@endsection
