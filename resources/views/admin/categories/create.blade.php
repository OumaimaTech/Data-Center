@extends('layouts.app')

@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="page-header">
    <h1>Créer une Nouvelle Catégorie</h1>
    <p>Ajoutez une catégorie pour organiser les ressources</p>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Nom de la catégorie *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Ex: Serveurs, Stockage, Réseau...">
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Description de la catégorie...">{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Créer la catégorie</button>
        </div>
    </form>
</div>
@endsection
