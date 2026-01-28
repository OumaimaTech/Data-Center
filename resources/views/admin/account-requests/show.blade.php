@extends('layouts.app')

@section('title', 'Demande de Compte #' . $accountRequest->id)

@section('content')
<div class="page-header">
    <h1>Demande de Compte #{{ $accountRequest->id }}</h1>
    <p>{{ $accountRequest->name }}</p>
</div>

<div class="grid grid-2">
    <div>
        <div class="card">
            <div class="card-header">
                <h3>Informations du Demandeur</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Nom complet:</strong> {{ $accountRequest->name }}
                </div>

                <div class="mb-3">
                    <strong>Email:</strong> {{ $accountRequest->email }}
                </div>

                @if($accountRequest->phone)
                    <div class="mb-3">
                        <strong>Téléphone:</strong> {{ $accountRequest->phone }}
                    </div>
                @endif

                @if($accountRequest->organization)
                    <div class="mb-3">
                        <strong>Organisation:</strong> {{ $accountRequest->organization }}
                    </div>
                @endif

                <div class="mb-3">
                    <strong>Date de la demande:</strong> {{ $accountRequest->created_at->format('d/m/Y à H:i') }}
                </div>

                <div class="mb-3">
                    <strong>Statut:</strong>
                    <span class="status-badge status-{{ $accountRequest->status }}">
                        {{ ucfirst(str_replace('_', ' ', $accountRequest->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Justification</h3>
            </div>
            <div class="card-body">
                <p>{{ $accountRequest->justification }}</p>
            </div>
        </div>
    </div>

    <div>
        @if($accountRequest->status === 'en_attente')
            <div class="card">
                <div class="card-header">
                    <h3>Actions</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.account-requests.approve', $accountRequest) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('Approuver cette demande et créer le compte utilisateur?')">
                            Approuver la Demande
                        </button>
                    </form>

                    <hr class="my-3">

                    <form action="{{ route('admin.account-requests.reject', $accountRequest) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Raison du refus</label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Expliquez pourquoi cette demande est refusée..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-full">
                            Refuser la Demande
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    <h3>Traitement de la Demande</h3>
                </div>
                <div class="card-body">
                    @if($accountRequest->processed_by)
                        <div class="mb-3">
                            <strong>Traitée par:</strong> {{ $accountRequest->processor->name }}
                        </div>
                    @endif

                    @if($accountRequest->processed_at)
                        <div class="mb-3">
                            <strong>Date de traitement:</strong> {{ $accountRequest->processed_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif

                    @if($accountRequest->rejection_reason)
                        <div class="mb-3">
                            <strong>Raison du refus:</strong>
                            <p class="mt-2">{{ $accountRequest->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($accountRequest->status === 'approuvee')
                        <div class="alert alert-success">
                            Cette demande a été approuvée. Un compte utilisateur a été créé.
                        </div>
                    @else
                        <div class="alert alert-error">
                            Cette demande a été refusée.
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.account-requests.index') }}" class="btn btn-secondary">
        ← Retour aux demandes
    </a>
</div>
@endsection
