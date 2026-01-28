@extends('layouts.app')

@section('title', $resource->name)

@section('content')
<div class="page-header">
    <h1>{{ $resource->name }}</h1>
    <p>{{ $resource->category->name }}</p>
</div>

<div class="grid grid-2">
    <div>
        <div class="card">
            <div class="card-header">
                <h3>Informations Générales</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Catégorie:</strong> {{ $resource->category->name }}
                </div>

                @if($resource->location)
                    <div class="mb-3">
                        <strong>Emplacement:</strong> {{ $resource->location }}
                    </div>
                @endif

                <div class="mb-3">
                    <strong>Statut:</strong>
                    <span class="status-badge status-{{ $resource->status }}">
                        {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                    </span>
                </div>

                @if($resource->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $resource->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($resource->specifications)
            <div class="card">
                <div class="card-header">
                    <h3>Spécifications Techniques</h3>
                </div>
                <div class="card-body">
                    <div class="resource-specs">
                        <dl>
                            @foreach($resource->specifications as $key => $value)
                                <dt>{{ ucfirst($key) }}:</dt>
                                <dd>{{ $value }}</dd>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div>
        <div class="card">
            <div class="card-header">
                <h3>Réservation</h3>
            </div>
            <div class="card-body text-center">
                <p>Pour réserver cette ressource, vous devez disposer d'un compte utilisateur.</p>
                <a href="{{ route('account-request.create') }}" class="btn btn-primary">
                    Demander un compte
                </a>
                <p class="mt-3">
                    <small class="text-muted">
                        Vous avez déjà un compte? 
                        <a href="{{ route('login') }}">Connectez-vous</a>
                    </small>
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Informations Utiles</h3>
            </div>
            <div class="card-body">
                <p>Pour plus d'informations sur les règles d'utilisation et les procédures de réservation:</p>
                <a href="{{ route('guest.info') }}" class="btn btn-outline w-full">
                    Consulter le guide
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('guest.resources') }}" class="btn btn-secondary">
        ← Retour aux ressources
    </a>
</div>
@endsection
