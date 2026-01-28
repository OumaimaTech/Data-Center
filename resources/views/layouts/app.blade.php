<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Data Center') - Gestion des Ressources</title>
    <link rel="stylesheet" href="{{ asset('css/modern-style.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="{{ route('home') }}">
                    Data Center
                </a>
            </div>
            <div class="navbar-menu">
                @auth
                    @php
                        $userRole = auth()->user()->role ? auth()->user()->role->name : null;
                    @endphp

                    @if($userRole === 'Invité')
                        <a href="{{ route('dashboard') }}">Accueil</a>
                        <a href="{{ route('guest.resources') }}">Ressources</a>
                        <a href="{{ route('guest.info') }}">Informations</a>
                        <a href="{{ route('account-request.create') }}">Demander un compte</a>
                    @endif

                    @if($userRole === 'Utilisateur interne')
                        <a href="{{ route('dashboard') }}">Tableau de bord</a>
                        <a href="{{ route('resources.index') }}">Ressources</a>
                        <a href="{{ route('reservations.index') }}">Mes Réservations</a>
                        <a href="{{ route('reservations.history') }}">Historique</a>
                        <a href="{{ route('incidents.index') }}">Incidents</a>
                        <a href="{{ route('notifications.index') }}">
                            Notifications
                            @php
                                $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    @endif

                    @if($userRole === 'Responsable technique')
                        <a href="{{ route('dashboard') }}">Tableau de bord</a>
                        <a href="{{ route('resources.index') }}">Mes Ressources</a>
                        <a href="{{ route('reservations.index') }}">Demandes</a>
                        <a href="{{ route('incidents.index') }}">Incidents</a>
                        <a href="{{ route('notifications.index') }}">
                            Notifications
                            @php
                                $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    @endif

                    @if($userRole === 'Administrateur')
                        <a href="{{ route('dashboard') }}">Tableau de bord</a>
                        <a href="{{ route('resources.index') }}">Ressources</a>
                        <a href="{{ route('reservations.index') }}">Réservations</a>
                        <a href="{{ route('incidents.index') }}">Incidents</a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle">Administration ▼</a>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
                                <a href="{{ route('admin.categories.index') }}">Catégories</a>
                                <a href="{{ route('admin.maintenance.index') }}">Maintenance</a>
                                <a href="{{ route('admin.statistics.index') }}">Statistiques</a>
                                <a href="{{ route('admin.account-requests.index') }}">Demandes de compte</a>
                            </div>
                        </div>
                        <a href="{{ route('notifications.index') }}">
                            Notifications
                            @php
                                $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    @endif

                    <div class="navbar-user">
                        <span>{{ auth()->user()->name }}</span>
                        @if(auth()->user()->role)
                            <span class="role-badge role-{{ strtolower(str_replace(' ', '-', $userRole)) }}">
                                {{ $userRole }}
                            </span>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-link">Déconnexion</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('guest.resources') }}">Ressources</a>
                    <a href="{{ route('guest.info') }}">Informations</a>
                    <a href="{{ route('account-request.create') }}">Demander un compte</a>
                    <a href="{{ route('login') }}">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Data Center - Gestion des Ressources. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
