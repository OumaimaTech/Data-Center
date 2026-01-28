@extends('layouts.app')

@section('title', 'Nouvelle réservation')

@section('content')
<div class="mb-4">
    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">← Retour à mes réservations</a>
</div>

<div class="card">
    <h1 class="card-header">Créer une demande de réservation</h1>
    <div class="card-body">
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="resource_id" class="form-label">Ressource *</label>
                <select 
                    id="resource_id" 
                    name="resource_id" 
                    class="form-control @error('resource_id') is-invalid @enderror" 
                    required
                >
                    <option value="">Sélectionner une ressource</option>
                    @foreach($resources as $resource)
                        <option 
                            value="{{ $resource->id }}" 
                            {{ old('resource_id', request('resource_id')) == $resource->id ? 'selected' : '' }}
                            data-category="{{ $resource->category->name }}"
                            data-location="{{ $resource->location }}"
                        >
                            {{ $resource->name }} ({{ $resource->category->name }})
                        </option>
                    @endforeach
                </select>
                @error('resource_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div id="resource-info" style="margin-top: 0.5rem; padding: 0.75rem; background-color: var(--light-bg); border-radius: 4px; display: none;">
                    <strong>Informations:</strong>
                    <div id="resource-details" style="margin-top: 0.5rem; font-size: 0.875rem;"></div>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="start_date" class="form-label">Date et heure de début *</label>
                    <input 
                        type="datetime-local" 
                        id="start_date" 
                        name="start_date" 
                        class="form-control @error('start_date') is-invalid @enderror" 
                        value="{{ old('start_date') }}" 
                        required
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                    >
                    @error('start_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date" class="form-label">Date et heure de fin *</label>
                    <input 
                        type="datetime-local" 
                        id="end_date" 
                        name="end_date" 
                        class="form-control @error('end_date') is-invalid @enderror" 
                        value="{{ old('end_date') }}" 
                        required
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                    >
                    @error('end_date')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="justification" class="form-label">Justification de la demande *</label>
                <textarea 
                    id="justification" 
                    name="justification" 
                    class="form-control @error('justification') is-invalid @enderror" 
                    rows="6"
                    required
                    placeholder="Veuillez expliquer en détail pourquoi vous avez besoin de cette ressource, le projet concerné, et comment elle sera utilisée..."
                >{{ old('justification') }}</textarea>
                @error('justification')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <small style="color: var(--secondary-color);">
                    Une justification claire et détaillée augmente les chances d'approbation de votre demande.
                </small>
            </div>

            <div style="padding: 1rem; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; margin-bottom: 1.5rem;">
                <strong>Important:</strong>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0;">
                    <li>Votre demande sera soumise à validation par le responsable technique</li>
                    <li>Assurez-vous que les dates ne chevauchent pas d'autres réservations</li>
                    <li>Vous recevrez une notification dès que votre demande sera traitée</li>
                    <li>Les réservations peuvent être annulées tant qu'elles sont en attente</li>
                </ul>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Soumettre la demande</button>
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('resource_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('resource-info');
    const detailsDiv = document.getElementById('resource-details');
    
    if (this.value) {
        const category = selectedOption.getAttribute('data-category');
        const location = selectedOption.getAttribute('data-location');
        
        let html = '<p><strong>Catégorie:</strong> ' + category + '</p>';
        if (location) {
            html += '<p><strong>Emplacement:</strong> ' + location + '</p>';
        }
        
        detailsDiv.innerHTML = html;
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
});

// Validate end date is after start date
document.getElementById('start_date').addEventListener('change', function() {
    const endDate = document.getElementById('end_date');
    endDate.min = this.value;
    
    if (endDate.value && endDate.value < this.value) {
        endDate.value = this.value;
    }
});

// Trigger resource info display if resource is pre-selected
if (document.getElementById('resource_id').value) {
    document.getElementById('resource_id').dispatchEvent(new Event('change'));
}
</script>
@endsection
