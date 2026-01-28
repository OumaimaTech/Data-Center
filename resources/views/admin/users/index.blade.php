@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="flex-between mb-4">
    <h1>Gestion des Utilisateurs</h1>
    <a href="{{ route('register') }}" class="btn btn-primary">Nouvel Utilisateur</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Utilisateurs</h3>
        <div class="stat-value">{{ $users->total() }}</div>
    </div>
    <div class="stat-card">
        <h3>Administrateurs</h3>
        <div class="stat-value">{{ ($adminRole = $roles->where('name', 'Administrateur')->first()) ? $adminRole->users->count() : 0 }}</div>
    </div>
    <div class="stat-card">
        <h3>Responsables</h3>
        <div class="stat-value">{{ ($managerRole = $roles->where('name', 'Responsable technique')->first()) ? $managerRole->users->count() : 0 }}</div>
    </div>
    <div class="stat-card">
        <h3>Utilisateurs</h3>
        <div class="stat-value">{{ ($userRole = $roles->where('name', 'Utilisateur interne')->first()) ? $userRole->users->count() : 0 }}</div>
    </div>
</div>

<div class="card">
    <h2 class="card-header">Liste des utilisateurs</h2>
    <div class="card-body">
        @if($users->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong><br>
                            <small style="color: var(--secondary-color);">ID: #{{ $user->id }}</small>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role->name === 'Administrateur')
                                <span class="status-badge" style="background-color: #fce4ec; color: #c2185b;">
                                    Administrateur
                                </span>
                            @elseif($user->role->name === 'Responsable technique')
                                <span class="status-badge" style="background-color: #fff3e0; color: #f57c00;">
                                    Responsable
                                </span>
                            @else
                                <span class="status-badge" style="background-color: #e8f5e9; color: #388e3c;">
                                    Utilisateur
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active ?? true)
                                <span class="status-badge status-approuvee">
                                    Actif
                                </span>
                            @else
                                <span class="status-badge status-refusee">
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $user->created_at->format('d/m/Y') }}</strong><br>
                            <small style="color: var(--secondary-color);">{{ $user->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($user->id !== auth()->id())
                                <div class="flex gap-1">
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-secondary" title="{{ $user->is_active ?? true ? 'Désactiver' : 'Activer' }}">
                                            {{ $user->is_active ?? true ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Attention : Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            @else
                                <span class="status-badge" style="background-color: #e3f2fd; color: #1976d2;">
                                    Vous
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                Aucun utilisateur trouvé.
            </p>
            <div style="text-align: center;">
                <a href="{{ route('register') }}" class="btn btn-primary">Ajouter un Utilisateur</a>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
