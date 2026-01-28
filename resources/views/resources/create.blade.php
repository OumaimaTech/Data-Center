@extends('layouts.app')

@section('title', 'Ajouter une ressource')

@section('content')
<div class="mb-4">
    <a href="{{ route('resources.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>

<div class="card">
    <h1 class="card-header">Ajouter une nouvelle ressource</h1>
    <div class="card-body">
        <form action="{{ route('resources.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom de la ressource *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    required
                >
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id" class="form-label">Catégorie *</label>
                <select 
                    id="category_id" 
                    name="category_id" 
                    class="form-control @error('category_id') is-invalid @enderror" 
                    required
                >
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control @error('description') is-invalid @enderror" 
                    rows="4"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="location" class="form-label">Emplacement</label>
                <input 
                    type="text" 
                    id="location" 
                    name="location" 
                    class="form-control @error('location') is-invalid @enderror" 
                    value="{{ old('location') }}" 
                    placeholder="Ex: Salle A, Rack 3, Position 12"
                >
                @error('location')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Statut *</label>
                <select 
                    id="status" 
                    name="status" 
                    class="form-control @error('status') is-invalid @enderror" 
                    required
                >
                    <option value="disponible" {{ old('status') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="en_maintenance" {{ old('status') == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                    <option value="indisponible" {{ old('status') == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                </select>
                @error('status')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Spécifications techniques</h3>
            <p style="color: var(--secondary-color); margin-bottom: 1rem;">Ajoutez les caractéristiques techniques de la ressource</p>

            <div id="specifications-container">
                <div class="spec-row" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <input 
                            type="text" 
                            name="spec_keys[]" 
                            class="form-control" 
                            placeholder="Nom (ex: CPU)"
                        >
                    </div>
                    <div style="flex: 1;">
                        <input 
                            type="text" 
                            name="spec_values[]" 
                            class="form-control" 
                            placeholder="Valeur (ex: Intel Xeon 16 cores)"
                        >
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)" style="display: none;">×</button>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" onclick="addSpec()">+ Ajouter une spécification</button>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Créer la ressource</button>
                <a href="{{ route('resources.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
function addSpec() {
    const container = document.getElementById('specifications-container');
    const newRow = document.createElement('div');
    newRow.className = 'spec-row';
    newRow.style.cssText = 'display: flex; gap: 1rem; margin-bottom: 1rem;';
    newRow.innerHTML = `
        <div style="flex: 1;">
            <input type="text" name="spec_keys[]" class="form-control" placeholder="Nom (ex: RAM)">
        </div>
        <div style="flex: 1;">
            <input type="text" name="spec_values[]" class="form-control" placeholder="Valeur (ex: 64 GB DDR4)">
        </div>
        <button type="button" class="btn btn-danger" onclick="removeSpec(this)">×</button>
    `;
    container.appendChild(newRow);
    
    // Show remove button on first row if there are multiple rows
    const firstRemoveBtn = container.querySelector('.spec-row:first-child button');
    if (firstRemoveBtn) {
        firstRemoveBtn.style.display = 'block';
    }
}

function removeSpec(button) {
    const container = document.getElementById('specifications-container');
    const row = button.closest('.spec-row');
    row.remove();
    
    // Hide remove button on first row if only one row remains
    const rows = container.querySelectorAll('.spec-row');
    if (rows.length === 1) {
        const firstRemoveBtn = rows[0].querySelector('button');
        if (firstRemoveBtn) {
            firstRemoveBtn.style.display = 'none';
        }
    }
}
</script>
@endsection
