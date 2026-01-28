@extends('layouts.app')

@section('title', 'Demande de Compte')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Demande d'Ouverture de Compte</h1>
            <p>Remplissez le formulaire ci-dessous pour demander l'accès au Data Center</p>
        </div>

        <form method="POST" action="{{ route('account-request.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nom complet *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                <span class="form-help">Utilisez votre email professionnel ou académique</span>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Organisation</label>
                <input type="text" name="organization" class="form-control" value="{{ old('organization') }}" placeholder="Université, Entreprise, Laboratoire...">
                @error('organization')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Justification de la demande *</label>
                <textarea name="justification" class="form-control" rows="6" required placeholder="Décrivez votre projet et pourquoi vous avez besoin d'accéder aux ressources du Data Center (minimum 50 caractères)">{{ old('justification') }}</textarea>
                <span class="form-help">Minimum 50 caractères. Soyez précis sur vos besoins.</span>
                @error('justification')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="alert alert-info">
                <strong>Note:</strong> Votre demande sera examinée par un administrateur. Vous recevrez une réponse par email dans les plus brefs délais.
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg">
                Soumettre la demande
            </button>
        </form>

        <div class="auth-footer">
            <p>Vous avez déjà un compte? <a href="{{ route('login') }}">Connectez-vous</a></p>
            <p><a href="{{ route('guest.resources') }}">← Retour aux ressources</a></p>
        </div>
    </div>
</div>
@endsection
