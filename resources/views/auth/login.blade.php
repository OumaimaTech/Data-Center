@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Connexion</h1>
            <p>Accédez à votre espace Data Center</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
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
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Se souvenir de moi</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Se connecter
            </button>
        </form>

        <div class="auth-footer">
            <p>Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
        </div>

        <!-- Comptes de démonstration -->
        <div style="margin-top: 2rem; padding: 1.5rem; background-color: var(--light-bg); border-radius: 8px; border-left: 4px solid var(--primary-color);">
            <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--primary-color);">
                <svg style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Comptes de test disponibles
            </h3>
            <div style="display: grid; gap: 0.75rem; font-size: 0.875rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: white; border-radius: 4px;">
                    <span><strong>Administrateur:</strong> admin@datacenter.com</span>
                    <button type="button" onclick="fillLogin('admin@datacenter.com', 'password123')" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Utiliser</button>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: white; border-radius: 4px;">
                    <span><strong>Responsable:</strong> manager.serveurs@datacenter.com</span>
                    <button type="button" onclick="fillLogin('manager.serveurs@datacenter.com', 'password123')" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Utiliser</button>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: white; border-radius: 4px;">
                    <span><strong>Utilisateur:</strong> jean.dupont@datacenter.com</span>
                    <button type="button" onclick="fillLogin('jean.dupont@datacenter.com', 'password123')" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Utiliser</button>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem; background: white; border-radius: 4px;">
                    <span><strong>Invité:</strong> invite@datacenter.com</span>
                    <button type="button" onclick="fillLogin('invite@datacenter.com', 'password123')" class="btn btn-sm btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Utiliser</button>
                </div>
            </div>
            <p style="margin-top: 1rem; font-size: 0.75rem; color: var(--secondary-color);">
                Mot de passe pour tous les comptes: <strong>password123</strong>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    // Scroll vers le haut pour voir le formulaire
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endpush
