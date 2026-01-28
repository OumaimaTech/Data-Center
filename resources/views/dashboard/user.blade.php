@extends('layouts.app')

@section('title', 'Tableau de bord Utilisateur')

@section('content')
<div class="flex-between mb-4">
    <h1>Tableau de bord</h1>
    <a href="{{ route('reservations.create') }}" class="btn btn-primary">Nouvelle réservation</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Mes Réservations</h3>
        <div class="stat-value">{{ $stats['my_reservations'] }}</div>
    </div>
    <div class="stat-card">
        <h3>En attente</h3>
        <div class="stat-value">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Approuvées</h3>
        <div class="stat-value">{{ $stats['approved'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Actives</h3>
        <div class="stat-value">{{ $stats['active'] }}</div>
    </div>
</div>

<div class="card mt-4">
    <h2 class="card-header">Mes réservations récentes</h2>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myReservations as $reservation)
                    <tr>
                        <td>
                            @if($reservation->resource)
                                <strong>{{ $reservation->resource->name }}</strong><br>
                                @if($reservation->resource->category)
                                    <small style="color: var(--secondary-color);">{{ $reservation->resource->category->name }}</small>
                                @endif
                            @else
                                <span style="color: var(--error-color);">Ressource supprimée</span>
                            @endif
                        </td>
                        <td>
                            {{ $reservation->start_date->format('d/m/Y H:i') }}<br>
                            <small>au {{ $reservation->end_date->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $reservation->status }}">
                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                            </span>
                        </td>
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
                        <td colspan="4" class="text-center">Aucune réservation</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($myReservations->count() > 0)
        <div class="card-footer">
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Voir toutes mes réservations</a>
        </div>
    @endif
</div>

<div class="card mt-4">
    <div class="flex-between">
        <h2 class="card-header">Ressources disponibles</h2>
        <span class="stat-value" style="font-size: 1.5rem;">{{ $availableResources }}</span>
    </div>
    <div class="card-footer">
        <a href="{{ route('resources.index') }}" class="btn btn-primary">Parcourir les ressources</a>
    </div>
</div>
@endsection
