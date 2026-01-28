@extends('layouts.app')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="page-header">
    <h1>Modifier la Catégorie</h1>
    <p>{{ $category->name }}</p>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nom de la catégorie *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="alert alert-info">
            <strong>Ressources associées:</strong> {{ $category->resources_count }} ressource(s)
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
