@extends('layouts.app')

@section('title', 'Mes réservations')

@section('content')
<div class="flex-between mb-4">
    <h1>Mes réservations</h1>
    @if(auth()->user()->role && auth()->user()->role->name === 'Utilisateur interne')
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">Nouvelle réservation</a>
    @endif
</div>

<div class="filter-section">
    <form action="{{ route('reservations.index') }}" method="GET" class="filter-form">
        <div class="form-group">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="approuvee" {{ request('status') == 'approuvee' ? 'selected' : '' }}>Approuvée</option>
                <option value="refusee" {{ request('status') == 'refusee' ? 'selected' : '' }}>Refusée</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="terminee" {{ request('status') == 'terminee' ? 'selected' : '' }}>Terminée</option>
            </select>
        </div>

        @if(auth()->user()->role && in_array(auth()->user()->role->name, ['Responsable technique', 'Administrateur']))
            <div class="form-group">
                <label for="resource_id" class="form-label">Ressource</label>
                <select name="resource_id" id="resource_id" class="form-control">
                    <option value="">Toutes les ressources</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}" {{ request('resource_id') == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }} ({{ $resource->category->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="user_id" class="form-label">Utilisateur</label>
                <select name="user_id" id="user_id" class="form-control">
                    <option value="">Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        
        <div class="form-group">
            <label for="date_from" class="form-label">Date de début</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        
        <div class="form-group">
            <label for="date_to" class="form-label">Date de fin</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label" style="visibility: hidden;">Action</label>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
        </div>

        @if(request()->hasAny(['status', 'resource_id', 'user_id', 'date_from', 'date_to']))
            <div class="form-group">
                <label class="form-label" style="visibility: hidden;">Réinitialiser</label>
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
            </div>
        @endif
    </form>
</div>

<div class="card">
    <h2 class="card-header">Liste de mes réservations</h2>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Ressource</th>
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
                            <strong>{{ $reservation->resource->name }}</strong><br>
                            <small style="color: var(--secondary-color);">{{ $reservation->resource->category->name }}</small>
                        </td>
                        <td>
                            <strong>Début:</strong> {{ $reservation->start_date->format('d/m/Y H:i') }}<br>
                            <strong>Fin:</strong> {{ $reservation->end_date->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <span class="status-badge status-{{ $reservation->status }}">
                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                            </span>
                            @if($reservation->status === 'refusee' && $reservation->rejection_reason)
                                <br><small style="color: var(--danger-color);" title="{{ $reservation->rejection_reason }}">
                                    Raison: {{ Str::limit($reservation->rejection_reason, 30) }}
                                </small>
                            @endif
                        </td>
                        <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">Voir</a>
                                @if($reservation->status === 'en_attente')
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" data-confirm="Êtes-vous sûr de vouloir annuler cette réservation ?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <p style="padding: 2rem; color: var(--secondary-color);">
                                Aucune réservation trouvée.
                            </p>
                            @if(auth()->user()->role && auth()->user()->role->name === 'Utilisateur interne')
                                <a href="{{ route('reservations.create') }}" class="btn btn-primary">Créer ma première réservation</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $reservations->links() }}
</div>

<div class="card mt-4">
    <h2 class="card-header">Légende des statuts</h2>
    <div class="card-body">
        <div class="grid grid-5">
            <div style="text-align: center;">
                <span class="status-badge status-en_attente">En attente</span>
                <p style="font-size: 0.875rem; margin-top: 0.5rem; color: var(--secondary-color);">
                    Demande soumise, en attente de validation
                </p>
            </div>
            <div style="text-align: center;">
                <span class="status-badge status-approuvee">Approuvée</span>
                <p style="font-size: 0.875rem; margin-top: 0.5rem; color: var(--secondary-color);">
                    Demande validée par le responsable
                </p>
            </div>
            <div style="text-align: center;">
                <span class="status-badge status-refusee">Refusée</span>
                <p style="font-size: 0.875rem; margin-top: 0.5rem; color: var(--secondary-color);">
                    Demande rejetée avec justification
                </p>
            </div>
            <div style="text-align: center;">
                <span class="status-badge status-active">Active</span>
                <p style="font-size: 0.875rem; margin-top: 0.5rem; color: var(--secondary-color);">
                    Réservation en cours d'utilisation
                </p>
            </div>
            <div style="text-align: center;">
                <span class="status-badge status-terminee">Terminée</span>
                <p style="font-size: 0.875rem; margin-top: 0.5rem; color: var(--secondary-color);">
                    Réservation terminée
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
