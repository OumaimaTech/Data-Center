@extends('layouts.app')

@section('title', 'Historique des réservations')

@section('content')
<div class="page-header">
    <h1>Historique de mes réservations</h1>
    <p>Consultez l'historique complet de toutes vos réservations</p>
</div>

<div class="filter-section">
    <form action="{{ route('reservations.history') }}" method="GET" class="filter-form">
        <div class="form-group">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="approuvee" {{ request('status') == 'approuvee' ? 'selected' : '' }}>Approuvée</option>
                <option value="refusee" {{ request('status') == 'refusee' ? 'selected' : '' }}>Refusée</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="resource_id" class="form-label">Ressource</label>
            <select name="resource_id" id="resource_id" class="form-control">
                <option value="">Toutes les ressources</option>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" {{ request('resource_id') == $resource->id ? 'selected' : '' }}>
                        {{ $resource->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="start_date" class="form-label">Date de début</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        
        <div class="form-group">
            <label for="end_date" class="form-label">Date de fin</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label" style="visibility: hidden;">Action</label>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Historique complet</h2>
        <span class="badge">{{ $reservations->total() }} réservation(s)</span>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Catégorie</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Date de demande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>
                                <strong>{{ $reservation->resource->name }}</strong>
                            </td>
                            <td>
                                <span style="color: var(--gray);">{{ $reservation->resource->category->name }}</span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <strong>Début:</strong> {{ $reservation->start_date->format('d/m/Y H:i') }}<br>
                                    <strong>Fin:</strong> {{ $reservation->end_date->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                </span>
                                @if($reservation->status === 'refusee' && $reservation->rejection_reason)
                                    <br><small style="color: var(--danger); font-size: 0.75rem;" title="{{ $reservation->rejection_reason }}">
                                        {{ Str::limit($reservation->rejection_reason, 30) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                {{ $reservation->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <p style="padding: 2rem; color: var(--gray);">
                                    Aucune réservation trouvée dans l'historique.
                                </p>
                                <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                                    Créer une réservation
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $reservations->links() }}
</div>

<div class="card mt-4">
    <div class="card-header">
        <h2>Statistiques</h2>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total</h3>
                <div class="stat-value">{{ $reservations->total() }}</div>
                <div class="stat-label">Réservations</div>
            </div>
            <div class="stat-card">
                <h3>En attente</h3>
                <div class="stat-value">{{ auth()->user()->reservations()->where('status', 'en_attente')->count() }}</div>
                <div class="stat-label">Demandes</div>
            </div>
            <div class="stat-card">
                <h3>Approuvées</h3>
                <div class="stat-value">{{ auth()->user()->reservations()->where('status', 'approuvee')->count() }}</div>
                <div class="stat-label">Validées</div>
            </div>
            <div class="stat-card">
                <h3>Refusées</h3>
                <div class="stat-value">{{ auth()->user()->reservations()->where('status', 'refusee')->count() }}</div>
                <div class="stat-label">Rejetées</div>
            </div>
        </div>
    </div>
</div>
@endsection
