@extends('layouts.app')

@section('title', 'Gestion des Incidents')

@section('content')
<div class="page-header-actions">
    <div>
        <h1>Gestion des Incidents</h1>
        <p>Suivez et gérez les incidents techniques</p>
    </div>
    @if(auth()->user()->role->name !== 'Responsable technique' || auth()->user()->role->name === 'Utilisateur interne')
        <a href="{{ route('incidents.create') }}" class="btn btn-primary">
            + Signaler un incident
        </a>
    @endif
</div>

<div class="filter-section">
    <form method="GET" action="{{ route('incidents.index') }}" class="filter-form">
        <div class="form-group">
            <label class="form-label">Statut</label>
            <select name="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="ouvert" {{ request('status') == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="resolu" {{ request('status') == 'resolu' ? 'selected' : '' }}>Résolu</option>
                <option value="ferme" {{ request('status') == 'ferme' ? 'selected' : '' }}>Fermé</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Priorité</label>
            <select name="priority" class="form-control">
                <option value="">Toutes les priorités</option>
                <option value="basse" {{ request('priority') == 'basse' ? 'selected' : '' }}>Basse</option>
                <option value="moyenne" {{ request('priority') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                <option value="haute" {{ request('priority') == 'haute' ? 'selected' : '' }}>Haute</option>
                <option value="critique" {{ request('priority') == 'critique' ? 'selected' : '' }}>Critique</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Ressource</label>
            <select name="resource_id" class="form-control">
                <option value="">Toutes les ressources</option>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" {{ request('resource_id') == $resource->id ? 'selected' : '' }}>
                        {{ $resource->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary w-full">Filtrer</button>
        </div>
    </form>
</div>

@if($incidents->count() > 0)
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Ressource</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Signalé par</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incidents as $incident)
                    <tr>
                        <td>#{{ $incident->id }}</td>
                        <td>{{ $incident->title }}</td>
                        <td>{{ $incident->resource->name }}</td>
                        <td>
                            <span class="status-badge status-{{ $incident->priority }}">
                                {{ ucfirst($incident->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $incident->status }}">
                                {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                            </span>
                        </td>
                        <td>{{ $incident->user->name }}</td>
                        <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('incidents.show', $incident) }}" class="btn btn-sm btn-outline">
                                    Voir
                                </a>
                                @if(auth()->user()->role->name === 'Administrateur' || 
                                    (auth()->user()->role->name === 'Utilisateur interne' && $incident->user_id === auth()->id()))
                                    <form action="{{ route('incidents.destroy', $incident) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $incidents->links() }}
    </div>
@else
    <div class="card text-center">
        <p>Aucun incident trouvé.</p>
    </div>
@endif
@endsection
