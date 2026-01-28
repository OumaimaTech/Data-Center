@extends('layouts.app')

@section('title', 'Gestion de la Maintenance')

@section('content')
<div class="page-header-actions">
    <div>
        <h1>Périodes de Maintenance</h1>
        <p>Planifiez et gérez les maintenances du Data Center</p>
    </div>
    <a href="{{ route('admin.maintenance.create') }}" class="btn btn-primary">
        + Planifier une maintenance
    </a>
</div>

<div class="filter-section">
    <form method="GET" action="{{ route('admin.maintenance.index') }}" class="filter-form">
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
            <label class="form-label">Période</label>
            <select name="period" class="form-control">
                <option value="">Toutes les périodes</option>
                <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                <option value="ongoing" {{ request('period') == 'ongoing' ? 'selected' : '' }}>En cours</option>
                <option value="past" {{ request('period') == 'past' ? 'selected' : '' }}>Passées</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary w-full">Filtrer</button>
        </div>
    </form>
</div>

@if($maintenances->count() > 0)
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ressource</th>
                    <th>Description</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Créé par</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenances as $maintenance)
                    @php
                        $now = now();
                        $status = 'upcoming';
                        if ($now->between($maintenance->start_date, $maintenance->end_date)) {
                            $status = 'ongoing';
                        } elseif ($now->gt($maintenance->end_date)) {
                            $status = 'past';
                        }
                    @endphp
                    <tr>
                        <td>#{{ $maintenance->id }}</td>
                        <td>{{ $maintenance->resource->name }}</td>
                        <td>{{ Str::limit($maintenance->description, 50) }}</td>
                        <td>{{ $maintenance->start_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $maintenance->end_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $maintenance->creator->name }}</td>
                        <td>
                            @if($status == 'ongoing')
                                <span class="status-badge status-en_maintenance">En cours</span>
                            @elseif($status == 'upcoming')
                                <span class="status-badge status-en_attente">À venir</span>
                            @else
                                <span class="status-badge status-indisponible">Terminée</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.maintenance.show', $maintenance) }}" class="btn btn-sm btn-outline">
                                    Voir
                                </a>
                                @if($status == 'upcoming')
                                    <a href="{{ route('admin.maintenance.edit', $maintenance) }}" class="btn btn-sm btn-outline">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.maintenance.destroy', $maintenance) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">
                                            Annuler
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
        {{ $maintenances->links() }}
    </div>
@else
    <div class="card text-center">
        <p>Aucune période de maintenance planifiée.</p>
    </div>
@endif
@endsection
