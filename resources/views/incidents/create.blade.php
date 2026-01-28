@extends('layouts.app')

@section('title', 'Signaler un Incident')

@section('content')
<div class="page-header">
    <h1>Signaler un Incident Technique</h1>
    <p>Décrivez le problème rencontré avec une ressource</p>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="{{ route('incidents.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Ressource concernée *</label>
            <select name="resource_id" class="form-control" required>
                <option value="">Sélectionnez une ressource</option>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : '' }}>
                        {{ $resource->name }} - {{ $resource->category->name }}
                    </option>
                @endforeach
            </select>
            @error('resource_id')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Titre de l'incident *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Ex: Serveur inaccessible, Erreur de connexion...">
            @error('title')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description détaillée *</label>
            <textarea name="description" class="form-control" rows="6" required placeholder="Décrivez le problème en détail: quand est-il survenu, quelles sont les erreurs affichées, etc.">{{ old('description') }}</textarea>
            <span class="form-help">Soyez le plus précis possible pour faciliter la résolution</span>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Priorité *</label>
            <select name="priority" class="form-control" required>
                <option value="">Sélectionnez la priorité</option>
                <option value="basse" {{ old('priority') == 'basse' ? 'selected' : '' }}>Basse - Problème mineur, pas d'urgence</option>
                <option value="moyenne" {{ old('priority') == 'moyenne' ? 'selected' : '' }}>Moyenne - Problème gênant mais contournable</option>
                <option value="haute" {{ old('priority') == 'haute' ? 'selected' : '' }}>Haute - Problème bloquant</option>
                <option value="critique" {{ old('priority') == 'critique' ? 'selected' : '' }}>Critique - Service complètement indisponible</option>
            </select>
            @error('priority')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="alert alert-info">
            <strong>Note:</strong> Votre incident sera transmis au responsable technique de la ressource et aux administrateurs. Vous recevrez une notification dès qu'une action sera entreprise.
        </div>

        <div class="card-footer">
            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Signaler l'incident</button>
        </div>
    </form>
</div>
@endsection
