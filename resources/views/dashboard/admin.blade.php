@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')

@section('content')
<div class="flex-between mb-4">
    <h1>Tableau de bord</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Gérer les utilisateurs</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Utilisateurs</h3>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Ressources</h3>
        <div class="stat-value">{{ $stats['total_resources'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Réservations en attente</h3>
        <div class="stat-value">{{ $stats['pending_reservations'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Réservations actives</h3>
        <div class="stat-value">{{ $stats['active_reservations'] }}</div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <h2 class="card-header">Taux d'occupation global</h2>
        <div class="card-body">
            <div style="text-align: center;">
                <div style="font-size: 3rem; font-weight: bold; color: var(--primary-color);">
                    {{ $occupationRate }}%
                </div>
                <p style="color: var(--secondary-color);">Ressources actuellement utilisées</p>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="card-header">Ressources par catégorie</h2>
        <div class="card-body">
            @foreach($resourcesByCategory as $category)
                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                        <span>{{ $category->name }}</span>
                        <span style="font-weight: bold;">{{ $category->resources_count }}</span>
                    </div>
                    <div style="background-color: var(--light-bg); height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="background-color: var(--primary-color); height: 100%; width: {{ $stats['total_resources'] > 0 ? ($category->resources_count / $stats['total_resources'] * 100) : 0 }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card mt-4">
    <h2 class="card-header">Réservations récentes</h2>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Ressource</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReservations as $reservation)
                    <tr>
                        <td>{{ $reservation->user ? $reservation->user->name : 'Utilisateur supprimé' }}</td>
                        <td>
                            @if($reservation->resource)
                                {{ $reservation->resource->name }}
                                @if($reservation->resource->category)
                                    <br><small style="color: var(--secondary-color);">{{ $reservation->resource->category->name }}</small>
                                @endif
                            @else
                                <span style="color: var(--error-color);">Ressource supprimée</span>
                            @endif
                        </td>
                        <td>{{ $reservation->start_date->format('d/m/Y') }} - {{ $reservation->end_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge status-{{ $reservation->status }}">
                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune réservation récente</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
