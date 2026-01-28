@extends('layouts.app')

@section('title', 'Ressources')

@section('content')
<div class="flex-between mb-4">
    <h1>Ressources du Data Center</h1>
    @if(auth()->check() && in_array(auth()->user()->role->name, ['Responsable technique', 'Administrateur']))
        <a href="{{ route('resources.create') }}" class="btn btn-primary">Ajouter une ressource</a>
    @endif
</div>

<div class="filter-section">
    <form action="{{ route('resources.index') }}" method="GET" class="filter-form">
        <div class="form-group">
            <label for="search" class="form-label">Rechercher</label>
            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Nom de la ressource...">
        </div>
        
        <div class="form-group">
            <label for="category_id" class="form-label">Catégorie</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="disponible" {{ request('status') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="en_maintenance" {{ request('status') == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                <option value="indisponible" {{ request('status') == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label" style="visibility: hidden;">Action</label>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
        </div>
    </form>
</div>

<div class="grid grid-3">
    @forelse($resources as $resource)
        <div class="resource-card">
            <h3>{{ $resource->name }}</h3>
            <div class="category">{{ $resource->category->name }}</div>
            
            @if($resource->description)
                <p style="color: var(--secondary-color); font-size: 0.875rem; margin: 1rem 0;">
                    {{ Str::limit($resource->description, 100) }}
                </p>
            @endif
            
            @if($resource->specifications)
                <div class="resource-specs">
                    <strong>Spécifications:</strong>
                    <dl style="margin-top: 0.5rem; font-size: 0.875rem;">
                        @php
                            $specs = is_string($resource->specifications) ? json_decode($resource->specifications, true) : $resource->specifications;
                            $specs = is_array($specs) ? array_slice($specs, 0, 3) : [];
                        @endphp
                        @foreach($specs as $key => $value)
                            <dt>{{ ucfirst($key) }}:</dt>
                            <dd>{{ $value }}</dd>
                        @endforeach
                    </dl>
                </div>
            @endif
            
            @if($resource->location)
                <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                    <strong>Location:</strong> {{ $resource->location }}
                </p>
            @endif
            
            <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <span class="status-badge status-{{ $resource->status }}">
                    {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                </span>
                
                <div class="flex gap-1">
                    <a href="{{ route('resources.show', $resource) }}" class="btn btn-sm btn-secondary">Voir</a>
                    @if(auth()->check() && in_array(auth()->user()->role->name, ['Responsable technique', 'Administrateur']))
                        <a href="{{ route('resources.edit', $resource) }}" class="btn btn-sm btn-primary">Modifier</a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
            <p style="color: var(--secondary-color); font-size: 1.125rem;">Aucune ressource trouvée.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $resources->links() }}
</div>
@endsection
