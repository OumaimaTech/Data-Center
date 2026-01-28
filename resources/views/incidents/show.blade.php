@extends('layouts.app')

@section('title', 'Incident #' . $incident->id)

@section('content')
<div class="page-header">
    <h1>Incident #{{ $incident->id }}: {{ $incident->title }}</h1>
    <p>{{ $incident->resource->name }}</p>
</div>

<div class="grid grid-2">
    <div>
        <div class="card">
            <div class="card-header">
                <h3>Détails de l'Incident</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Ressource:</strong> {{ $incident->resource->name }}
                </div>

                <div class="mb-3">
                    <strong>Signalé par:</strong> {{ $incident->user->name }}
                </div>

                <div class="mb-3">
                    <strong>Date:</strong> {{ $incident->created_at->format('d/m/Y à H:i') }}
                </div>

                <div class="mb-3">
                    <strong>Priorité:</strong>
                    <span class="status-badge status-{{ $incident->priority }}">
                        {{ ucfirst($incident->priority) }}
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Statut:</strong>
                    <span class="status-badge status-{{ $incident->status }}">
                        {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Description:</strong>
                    <p class="mt-2">{{ $incident->description }}</p>
                </div>

                @if($incident->resolution_notes)
                    <div class="mb-3">
                        <strong>Notes de résolution:</strong>
                        <p class="mt-2">{{ $incident->resolution_notes }}</p>
                    </div>
                @endif

                @if($incident->resolved_by)
                    <div class="mb-3">
                        <strong>Résolu par:</strong> {{ $incident->resolver->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Date de résolution:</strong> {{ $incident->resolved_at->format('d/m/Y à H:i') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        @if(auth()->user()->role->name === 'Responsable technique' || auth()->user()->role->name === 'Administrateur')
            @if($incident->status !== 'resolu' && $incident->status !== 'ferme')
                <div class="card">
                    <div class="card-header">
                        <h3>Actions</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('incidents.update-status', $incident) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label class="form-label">Changer le statut</label>
                                <select name="status" class="form-control">
                                    <option value="ouvert" {{ $incident->status == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                                    <option value="en_cours" {{ $incident->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="ferme" {{ $incident->status == 'ferme' ? 'selected' : '' }}>Fermé</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-full">Mettre à jour</button>
                        </form>

                        <hr class="my-3">

                        <form method="POST" action="{{ route('incidents.resolve', $incident) }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Marquer comme résolu</label>
                                <textarea name="resolution_notes" class="form-control" rows="4" placeholder="Décrivez la solution apportée..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-full">Résoudre l'incident</button>
                        </form>
                    </div>
                </div>
            @endif
        @endif

        <div class="card">
            <div class="card-header">
                <h3>Informations sur la Ressource</h3>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Nom:</strong> {{ $incident->resource->name }}
                </div>
                <div class="mb-2">
                    <strong>Catégorie:</strong> {{ $incident->resource->category->name }}
                </div>
                @if($incident->resource->location)
                    <div class="mb-2">
                        <strong>Emplacement:</strong> {{ $incident->resource->location }}
                    </div>
                @endif
                <div class="mb-2">
                    <strong>Statut:</strong>
                    <span class="status-badge status-{{ $incident->resource->status }}">
                        {{ ucfirst(str_replace('_', ' ', $incident->resource->status)) }}
                    </span>
                </div>
                <div class="mt-3">
                    <a href="{{ route('resources.show', $incident->resource) }}" class="btn btn-outline w-full">
                        Voir la ressource
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('incidents.index') }}" class="btn btn-secondary">← Retour aux incidents</a>
    
    @if(auth()->user()->role->name === 'Administrateur' || $incident->user_id === auth()->id())
        <form action="{{ route('incidents.destroy', $incident) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet incident?')">
                Supprimer
            </button>
        </form>
    @endif
</div>
@endsection
