@extends('layouts.app')

@section('title', 'Planifier une Maintenance')

@section('content')
<div class="page-header">
    <h1>Planifier une Période de Maintenance</h1>
    <p>Définissez une période de maintenance pour une ressource</p>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="{{ route('admin.maintenance.store') }}">
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
            <label class="form-label">Description de la maintenance *</label>
            <textarea name="description" class="form-control" rows="4" required placeholder="Décrivez les travaux de maintenance prévus...">{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">Date et heure de début *</label>
                <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                @error('start_date')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date et heure de fin *</label>
                <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                @error('end_date')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="alert alert-warning">
            <strong>Attention:</strong> Les utilisateurs ayant des réservations actives sur cette ressource pendant cette période seront automatiquement notifiés.
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.maintenance.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Planifier la maintenance</button>
        </div>
    </form>
</div>
@endsection
