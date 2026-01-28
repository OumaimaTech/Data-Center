@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('content')
<div class="mb-4">
    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>

<div class="card">
    <div class="flex-between">
        <h1 class="card-header">Détails de la réservation #{{ $reservation->id }}</h1>
        <span class="status-badge status-{{ $reservation->status }}">
            {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
        </span>
    </div>

    <div class="card-body">
        <div class="grid grid-2">
            <div>
                <h3 style="margin-bottom: 1rem;">Informations de la réservation</h3>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Ressource:</strong><br>
                    <a href="{{ route('resources.show', $reservation->resource) }}" style="color: var(--primary-color);">
                        {{ $reservation->resource->name }}
                    </a>
                    <span class="category">{{ $reservation->resource->category->name }}</span>
                </div>

                <div style="margin-bottom: 1rem;">
                    <strong>Demandeur:</strong><br>
                    {{ $reservation->user->name }}<br>
                    <small style="color: var(--secondary-color);">{{ $reservation->user->email }}</small>
                </div>

                <div style="margin-bottom: 1rem;">
                    <strong>Période de réservation:</strong><br>
                    <strong>Début:</strong> {{ $reservation->start_date->format('d/m/Y à H:i') }}<br>
                    <strong>Fin:</strong> {{ $reservation->end_date->format('d/m/Y à H:i') }}<br>
                    <small style="color: var(--secondary-color);">
                        Durée: {{ $reservation->start_date->diffInDays($reservation->end_date) }} jour(s)
                    </small>
                </div>

                <div style="margin-bottom: 1rem;">
                    <strong>Date de demande:</strong><br>
                    {{ $reservation->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>

            <div>
                <h3 style="margin-bottom: 1rem;">Justification</h3>
                <div style="padding: 1rem; background-color: var(--light-bg); border-radius: 6px; white-space: pre-wrap;">
                    {{ $reservation->justification }}
                </div>

                @if($reservation->status === 'approuvee' && $reservation->approved_by)
                    <div style="margin-top: 1.5rem; padding: 1rem; background-color: #d4edda; border: 1px solid #28a745; border-radius: 6px;">
                        <strong>Approuvée par:</strong><br>
                        {{ $reservation->approver->name }}<br>
                        @if($reservation->approved_at)
                            <small>Le {{ $reservation->approved_at->format('d/m/Y à H:i') }}</small>
                        @endif
                    </div>
                @endif

                @if($reservation->status === 'refusee')
                    <div style="margin-top: 1.5rem; padding: 1rem; background-color: #f8d7da; border: 1px solid #dc3545; border-radius: 6px;">
                        <strong>Refusée</strong>
                        @if($reservation->approved_by)
                            <br>Par: {{ $reservation->approver->name }}
                            @if($reservation->approved_at)
                                <br><small>Le {{ $reservation->approved_at->format('d/m/Y à H:i') }}</small>
                            @endif
                        @endif
                        @if($reservation->rejection_reason)
                            <br><br><strong>Raison du refus:</strong>
                            <p style="margin-top: 0.5rem; white-space: pre-wrap;">{{ $reservation->rejection_reason }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user()->role->name === 'Responsable technique' && $reservation->status === 'en_attente')
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: var(--light-bg); border-radius: 6px;">
                <h3 style="margin-bottom: 1rem;">Actions du responsable</h3>
                <div class="flex gap-2">
                    <form action="{{ route('reservations.approve', $reservation) }}" method="POST" data-confirm="Êtes-vous sûr de vouloir approuver cette réservation ?">
                        @csrf
                        <button type="submit" class="btn btn-success">Approuver la demande</button>
                    </form>
                    <button type="button" class="btn btn-danger" onclick="showRejectModal()">Refuser la demande</button>
                </div>
            </div>
        @endif

        @if(auth()->id() === $reservation->user_id && $reservation->status === 'en_attente')
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 6px;">
                <strong>Attention - Demande en attente</strong>
                <p style="margin-top: 0.5rem; margin-bottom: 1rem;">
                    Votre demande est en cours de traitement. Vous pouvez l'annuler si nécessaire.
                </p>
                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" data-confirm="Êtes-vous sûr de vouloir annuler cette réservation ?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Annuler la réservation</button>
                </form>
            </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <h2 class="card-header">Informations système</h2>
    <div class="card-body">
        <div class="grid grid-3">
            <div>
                <strong>ID de réservation:</strong>
                <span>#{{ $reservation->id }}</span>
            </div>
            <div>
                <strong>Créée le:</strong>
                <span>{{ $reservation->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div>
                <strong>Dernière modification:</strong>
                <span>{{ $reservation->updated_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal de refus -->
<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem;">Refuser la réservation</h3>
        <form action="{{ route('reservations.reject', $reservation) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="rejection_reason" class="form-label">Raison du refus *</label>
                <textarea 
                    name="rejection_reason" 
                    id="rejection_reason" 
                    class="form-control" 
                    rows="5"
                    required
                    placeholder="Expliquez pourquoi cette demande est refusée..."
                ></textarea>
            </div>
            <div class="flex gap-2" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Annuler</button>
                <button type="submit" class="btn btn-danger">Confirmer le refus</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejection_reason').value = '';
}

// Close modal on outside click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
