@extends('layouts.app')

@section('title', $resource->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('resources.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>

<div class="card">
    <div class="flex-between">
        <h1 class="card-header">{{ $resource->name }}</h1>
        <div class="flex gap-1">
            @if(auth()->check() && in_array(auth()->user()->role->name, ['Responsable technique', 'Administrateur']))
                <a href="{{ route('resources.edit', $resource) }}" class="btn btn-primary">Modifier</a>
                <form action="{{ route('resources.destroy', $resource) }}" method="POST" data-confirm="Êtes-vous sûr de vouloir supprimer cette ressource ?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card-body">
        <div class="grid grid-2">
            <div>
                <h3 style="margin-bottom: 1rem;">Informations générales</h3>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Catégorie:</strong>
                    <span class="category">{{ $resource->category->name }}</span>
                </div>

                <div style="margin-bottom: 1rem;">
                    <strong>Statut:</strong>
                    <span class="status-badge status-{{ $resource->status }}">
                        {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                    </span>
                </div>

                @if($resource->location)
                    <div style="margin-bottom: 1rem;">
                        <strong>Emplacement:</strong>
                        <span>{{ $resource->location }}</span>
                    </div>
                @endif

                @if($resource->description)
                    <div style="margin-top: 1.5rem;">
                        <strong>Description:</strong>
                        <p style="margin-top: 0.5rem; color: var(--secondary-color);">
                            {{ $resource->description }}
                        </p>
                    </div>
                @endif
            </div>

            <div>
                @php
                    // Handle specifications - ensure it's an array
                    $specs = $resource->specifications;
                    if (is_string($specs)) {
                        $specs = json_decode($specs, true) ?? [];
                    }
                    $specs = is_array($specs) ? $specs : [];
                @endphp
                
                @if(!empty($specs))
                    <h3 style="margin-bottom: 1rem;">Spécifications techniques</h3>
                    <div class="resource-specs">
                        <dl>
                            @foreach($specs as $key => $value)
                                <dt>{{ ucfirst($key) }}:</dt>
                                <dd>{{ $value }}</dd>
                            @endforeach
                        </dl>
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->check() && auth()->user()->role->name === 'Utilisateur interne' && $resource->status === 'disponible')
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: var(--light-bg); border-radius: 6px;">
                <h3 style="margin-bottom: 1rem;">Réserver cette ressource</h3>
                <p style="color: var(--secondary-color); margin-bottom: 1rem;">
                    Cette ressource est disponible pour réservation. Cliquez sur le bouton ci-dessous pour créer une demande.
                </p>
                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-primary">
                    Créer une réservation
                </a>
            </div>
        @endif
    </div>
</div>

@if(auth()->check() && in_array(auth()->user()->role->name, ['Responsable technique', 'Administrateur']))
    <div class="card mt-4">
        <h2 class="card-header">Historique des réservations</h2>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Date de demande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resource->reservations()->latest()->take(10)->get() as $reservation)
                        <tr>
                            <td>{{ $reservation->user->name }}</td>
                            <td>
                                {{ $reservation->start_date->format('d/m/Y H:i') }}<br>
                                <small>au {{ $reservation->end_date->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                </span>
                            </td>
                            <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucune réservation pour cette ressource</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="card mt-4">
    <h2 class="card-header">Informations système</h2>
    <div class="card-body">
        <div class="grid grid-2">
            <div>
                <strong>Créée le:</strong>
                <span>{{ $resource->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <strong>Dernière modification:</strong>
                <span>{{ $resource->updated_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
