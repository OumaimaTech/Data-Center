@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="mb-4">
    <h1>Tableau de bord</h1>
    <p style="color: var(--secondary-color);">Consultez les ressources du Data Center. Pour effectuer une réservation, veuillez vous connecter avec un compte utilisateur.</p>
</div>

<div class="card mb-4">
    <h2 class="card-header">Catégories de ressources</h2>
    <div class="grid grid-4 mt-3">
        @foreach($categories as $category)
            <div class="stat-card">
                <h3>{{ $category->name }}</h3>
                <div class="stat-value">{{ $category->resources_count }}</div>
                <p style="font-size: 0.875rem; color: var(--secondary-color);">ressources</p>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <h2 class="card-header">Liste des ressources disponibles</h2>
    <div class="card-body">
        <div class="grid grid-3">
            @forelse($availableResources as $resource)
                <div class="resource-card">
                    <h3>{{ $resource->name }}</h3>
                    <div class="category">{{ $resource->category->name }}</div>
                    
                    @if($resource->description)
                        <p style="color: var(--secondary-color); font-size: 0.875rem;">
                            {{ Str::limit($resource->description, 100) }}
                        </p>
                    @endif
                    
                    @php
                        // Handle specifications - ensure it's an array
                        $specs = $resource->specifications;
                        if (is_string($specs)) {
                            $specs = json_decode($specs, true) ?? [];
                        }
                        $specs = is_array($specs) ? $specs : [];
                    @endphp
                    
                    @if(!empty($specs))
                        <div class="resource-specs">
                            <strong>Spécifications:</strong>
                            <ul style="margin-left: 1.5rem; margin-top: 0.5rem; font-size: 0.875rem;">
                                @foreach($specs as $key => $value)
                                    <li>{{ ucfirst($key) }}: {{ $value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if($resource->location)
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                            <strong>Emplacement:</strong> {{ $resource->location }}
                        </p>
                    @endif
                    
                    <div style="margin-top: 1rem;">
                        <span class="status-badge status-{{ $resource->status }}">
                            {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                        </span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                    <p style="color: var(--secondary-color);">Aucune ressource disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="card mt-4">
    <h2 class="card-header">Informations importantes</h2>
    <div class="card-body">
        <h3 style="margin-bottom: 1rem;">Pour réserver une ressource :</h3>
        <ol style="margin-left: 2rem; line-height: 2;">
            <li>Créez un compte utilisateur ou connectez-vous</li>
            <li>Parcourez les ressources disponibles</li>
            <li>Sélectionnez la ressource souhaitée</li>
            <li>Remplissez le formulaire de réservation avec justification</li>
            <li>Attendez l'approbation du responsable technique</li>
        </ol>
        
        <div style="margin-top: 2rem; padding: 1rem; background-color: var(--light-bg); border-radius: 6px;">
            <strong>Note:</strong> Les réservations sont soumises à validation. Assurez-vous de fournir une justification claire pour votre demande.
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('register') }}" class="btn btn-primary">Créer un compte</a>
        <a href="{{ route('login') }}" class="btn btn-secondary">Se connecter</a>
    </div>
</div>
@endsection
