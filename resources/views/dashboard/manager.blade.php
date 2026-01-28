@extends('layouts.app')

@section('title', 'Tableau de bord Responsable Technique')

@section('content')
<div class="flex-between mb-4">
    <h1>Tableau de bord</h1>
    <a href="{{ route('resources.create') }}" class="btn btn-primary">Ajouter une ressource</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Mes Ressources</h3>
        <div class="stat-value">{{ $stats['my_resources'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Demandes en attente</h3>
        <div class="stat-value">{{ $stats['pending_requests'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Réservations actives</h3>
        <div class="stat-value">{{ $stats['active_reservations'] }}</div>
    </div>
</div>

<div class="card mt-4">
    <h2 class="card-header">Demandes de réservation en attente</h2>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Ressource</th>
                    <th>Période</th>
                    <th>Justification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingReservations as $reservation)
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
                        <td>
                            {{ $reservation->start_date->format('d/m/Y') }}<br>
                            <small>au {{ $reservation->end_date->format('d/m/Y') }}</small>
                        </td>
                        <td>{{ Str::limit($reservation->justification, 50) }}</td>
                        <td>
                            <div class="flex gap-1">
                                @if($reservation->resource)
                                    <form action="{{ route('reservations.approve', $reservation) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal({{ $reservation->id }})">Refuser</button>
                                @endif
                                <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-sm btn-secondary">Détails</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune demande en attente</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="flex-between">
        <h2 class="card-header">Mes Ressources</h2>
        <a href="{{ route('resources.index') }}" class="btn btn-secondary">Voir toutes</a>
    </div>
</div>

<!-- Modal de refus (simple) -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Refuser la réservation</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="rejection_reason" class="form-label">Raison du refus</label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-control" required></textarea>
            </div>
            <div class="flex gap-2" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Annuler</button>
                <button type="submit" class="btn btn-danger">Confirmer le refus</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(reservationId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '/reservations/' + reservationId + '/reject';
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejection_reason').value = '';
}
</script>
@endpush
@endsection
