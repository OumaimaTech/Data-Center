@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Inscription</h1>
            <p>Créez votre compte Data Center</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                >
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    required
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    required
                >
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <small style="color: var(--secondary-color);">Minimum 8 caractères</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="user_type" class="form-label">Type d'utilisateur *</label>
                <select 
                    id="user_type" 
                    name="user_type" 
                    class="form-control @error('user_type') is-invalid @enderror" 
                    required
                >
                    <option value="">Sélectionner votre profil</option>
                    <option value="Invité" {{ old('user_type') == 'Invité' ? 'selected' : '' }}>Invité - Consultation uniquement</option>
                    <option value="Utilisateur interne" {{ old('user_type') == 'Utilisateur interne' ? 'selected' : '' }}>Utilisateur interne - Ingénieur/Enseignant/Doctorant</option>
                    <option value="Responsable technique" {{ old('user_type') == 'Responsable technique' ? 'selected' : '' }}>Responsable technique - Gestion des ressources</option>
                </select>
                @error('user_type')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <small style="color: var(--secondary-color); display: block; margin-top: 0.5rem;">
                    Choisissez le profil correspondant à votre fonction
                </small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                S'inscrire
            </button>
        </form>

        <div class="auth-footer">
            <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </div>
</div>
@endsection
