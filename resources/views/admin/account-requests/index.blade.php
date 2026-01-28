@extends('layouts.app')

@section('title', 'Demandes de Compte')

@section('content')
<div class="page-header">
    <h1>Demandes d'Ouverture de Compte</h1>
    <p>Gérez les demandes d'accès au Data Center</p>
</div>

<div class="filter-section">
    <form method="GET" action="{{ route('admin.account-requests.index') }}" class="filter-form">
        <div class="form-group">
            <label class="form-label">Statut</label>
            <select name="status" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="approuvee" {{ request('status') == 'approuvee' ? 'selected' : '' }}>Approuvée</option>
                <option value="refusee" {{ request('status') == 'refusee' ? 'selected' : '' }}>Refusée</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary w-full">Filtrer</button>
        </div>
    </form>
</div>

@if($requests->count() > 0)
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Organisation</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td>#{{ $request->id }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ $request->organization ?? '-' }}</td>
                        <td>{{ $request->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge status-{{ $request->status }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.account-requests.show', $request) }}" class="btn btn-sm btn-outline">
                                    Voir
                                </a>
                                @if($request->status === 'en_attente')
                                    <form action="{{ route('admin.account-requests.approve', $request) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approuver cette demande?')">
                                            Approuver
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal({{ $request->id }})">
                                        Refuser
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
@else
    <div class="card text-center">
        <p>Aucune demande de compte trouvée.</p>
    </div>
@endif

<div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="max-width: 500px; margin: 2rem;">
        <div class="card-header">
            <h3>Refuser la Demande</h3>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Raison du refus *</label>
                    <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Expliquez pourquoi cette demande est refusée..."></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Annuler</button>
                <button type="submit" class="btn btn-danger">Refuser la demande</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(requestId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/admin/account-requests/${requestId}/reject`;
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endpush
@endsection
