@extends('layouts.app')

@section('title', 'Ressources Disponibles')

@section('content')
<div class="page-header">
    <h1>Ressources Disponibles</h1>
    <p>Consultez les ressources du Data Center en mode lecture seule</p>
</div>

<div class="filter-section">
    <form method="GET" action="{{ route('guest.resources') }}" class="filter-form">
        <div class="form-group">
            <label class="form-label">Catégorie</label>
            <select name="category_id" class="form-control">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }} ({{ $category->resources_count }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Recherche</label>
            <input type="text" name="search" class="form-control" placeholder="Nom de la ressource..." value="{{ request('search') }}">
        </div>

        <div class="form-group">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary w-full">Filtrer</button>
        </div>
    </form>
</div>

@if($resources->count() > 0)
    <div class="grid grid-3">
        @foreach($resources as $resource)
            <div class="resource-card">
                <h3>{{ $resource->name }}</h3>
                <p class="category">{{ $resource->category->name }}</p>
                
                <div class="resource-specs">
                    @if($resource->specifications)
                        <dl>
                            @foreach($resource->specifications as $key => $value)
                                <dt>{{ ucfirst($key) }}:</dt>
                                <dd>{{ $value }}</dd>
                            @endforeach
                        </dl>
                    @endif
                </div>

                @if($resource->location)
                    <p><strong>Emplacement:</strong> {{ $resource->location }}</p>
                @endif

                <div class="mt-3">
                    <span class="status-badge status-{{ $resource->status }}">
                        {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                    </span>
                </div>

                <div class="mt-3">
                    <a href="{{ route('guest.show', $resource) }}" class="btn btn-outline btn-sm w-full">
                        Voir les détails
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $resources->links() }}
    </div>
@else
    <div class="card text-center">
        <p>Aucune ressource disponible pour le moment.</p>
    </div>
@endif

<div class="card mt-4">
    <div class="card-header">
        <h3>Vous souhaitez réserver une ressource?</h3>
    </div>
    <div class="card-body">
        <p>Pour pouvoir faire des réservations, vous devez créer un compte utilisateur.</p>
        <a href="{{ route('account-request.create') }}" class="btn btn-primary">
            Demander un compte
        </a>
    </div>
</div>
@endsection
