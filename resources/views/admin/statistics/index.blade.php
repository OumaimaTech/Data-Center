@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="page-header">
    <h1>Statistiques du Data Center</h1>
    <p>Vue d'ensemble de l'utilisation et des performances</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Ressources</h3>
        <div class="stat-value">{{ $stats['total_resources'] }}</div>
        <div class="stat-change">
            {{ $stats['active_resources'] }} actives
        </div>
    </div>

    <div class="stat-card">
        <h3>Réservations Actives</h3>
        <div class="stat-value">{{ $stats['active_reservations'] }}</div>
        <div class="stat-change">
            {{ $stats['pending_reservations'] }} en attente
        </div>
    </div>

    <div class="stat-card">
        <h3>Utilisateurs</h3>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-change">
            {{ $stats['active_users'] }} actifs
        </div>
    </div>

    <div class="stat-card">
        <h3>Incidents Ouverts</h3>
        <div class="stat-value">{{ $stats['open_incidents'] }}</div>
        <div class="stat-change">
            {{ $stats['total_incidents'] }} au total
        </div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3>Taux d'Occupation Global</h3>
        </div>
        <div class="card-body">
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; font-weight: bold; color: var(--primary);">
                    {{ $stats['occupation_rate'] }}%
                </div>
                <p class="mt-2">{{ $stats['occupied_resources'] }} / {{ $stats['total_resources'] }} ressources occupées</p>
            </div>
            
            <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                <div style="background: var(--primary); height: 20px; border-radius: 10px; width: {{ $stats['occupation_rate'] }}%;"></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Répartition par Statut</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="flex-between mb-2">
                    <span>Disponibles</span>
                    <strong>{{ $stats['available_resources'] }}</strong>
                </div>
                <div style="background: #f8fafc; height: 10px; border-radius: 5px;">
                    <div style="background: #10b981; height: 100%; border-radius: 5px; width: {{ $stats['total_resources'] > 0 ? ($stats['available_resources'] / $stats['total_resources'] * 100) : 0 }}%;"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex-between mb-2">
                    <span>En maintenance</span>
                    <strong>{{ $stats['maintenance_resources'] }}</strong>
                </div>
                <div style="background: #f8fafc; height: 10px; border-radius: 5px;">
                    <div style="background: #f59e0b; height: 100%; border-radius: 5px; width: {{ $stats['total_resources'] > 0 ? ($stats['maintenance_resources'] / $stats['total_resources'] * 100) : 0 }}%;"></div>
                </div>
            </div>

            <div class="mb-3">
                <div class="flex-between mb-2">
                    <span>Indisponibles</span>
                    <strong>{{ $stats['unavailable_resources'] }}</strong>
                </div>
                <div style="background: #f8fafc; height: 10px; border-radius: 5px;">
                    <div style="background: #ef4444; height: 100%; border-radius: 5px; width: {{ $stats['total_resources'] > 0 ? ($stats['unavailable_resources'] / $stats['total_resources'] * 100) : 0 }}%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3>Ressources les Plus Demandées</h3>
        </div>
        <div class="card-body">
            @if($stats['top_resources']->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ressource</th>
                                <th>Réservations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['top_resources'] as $resource)
                                <tr>
                                    <td>{{ $resource->name }}</td>
                                    <td><strong>{{ $resource->reservations_count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Aucune donnée disponible</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Utilisateurs les Plus Actifs</h3>
        </div>
        <div class="card-body">
            @if($stats['top_users']->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Réservations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['top_users'] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td><strong>{{ $user->reservations_count }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Aucune donnée disponible</p>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Réservations Récentes</h3>
    </div>
    <div class="card-body">
        @if($stats['recent_reservations']->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Ressource</th>
                            <th>Période</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recent_reservations'] as $reservation)
                            <tr>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->resource->name }}</td>
                                <td>{{ $reservation->start_date->format('d/m/Y') }} - {{ $reservation->end_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $reservation->status }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">Aucune réservation récente</p>
        @endif
    </div>
</div>
@endsection
