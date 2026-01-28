@extends('layouts.app')

@section('title', 'Informations et Règles')

@section('content')
<div class="page-header">
    <h1>Guide d'Utilisation du Data Center</h1>
    <p>Informations sur les ressources disponibles et les règles d'utilisation</p>
</div>

<div class="grid grid-2">
    <div>
        <div class="card">
            <div class="card-header">
                <h3>Ressources Disponibles</h3>
            </div>
            <div class="card-body">
                <h4>Types de Ressources</h4>
                <ul>
                    <li><strong>Serveurs:</strong> Serveurs physiques et virtuels pour vos projets</li>
                    <li><strong>Stockage:</strong> Espaces de stockage sécurisés</li>
                    <li><strong>Équipements Réseau:</strong> Switches, routeurs, pare-feu</li>
                    <li><strong>Stations de Travail:</strong> Postes de travail haute performance</li>
                </ul>

                <h4 class="mt-3">Caractéristiques</h4>
                <p>Chaque ressource dispose de spécifications techniques détaillées incluant:</p>
                <ul>
                    <li>Processeur et mémoire RAM</li>
                    <li>Capacité de stockage</li>
                    <li>Système d'exploitation</li>
                    <li>Connectivité réseau</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Procédure de Réservation</h3>
            </div>
            <div class="card-body">
                <ol>
                    <li><strong>Créer un compte:</strong> Demandez l'ouverture d'un compte utilisateur</li>
                    <li><strong>Connexion:</strong> Connectez-vous avec vos identifiants</li>
                    <li><strong>Sélection:</strong> Parcourez les ressources disponibles</li>
                    <li><strong>Réservation:</strong> Faites une demande en précisant:
                        <ul>
                            <li>Période souhaitée (dates de début et fin)</li>
                            <li>Ressource(s) nécessaire(s)</li>
                            <li>Justification du besoin</li>
                        </ul>
                    </li>
                    <li><strong>Validation:</strong> Attendez l'approbation du responsable technique</li>
                    <li><strong>Utilisation:</strong> Accédez à votre ressource une fois approuvée</li>
                </ol>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header">
                <h3>Règles d'Utilisation</h3>
            </div>
            <div class="card-body">
                <h4>Obligations des Utilisateurs</h4>
                <ul>
                    <li>Respecter les périodes de réservation</li>
                    <li>Utiliser les ressources conformément à leur destination</li>
                    <li>Signaler immédiatement tout problème technique</li>
                    <li>Ne pas modifier les configurations système sans autorisation</li>
                    <li>Sauvegarder régulièrement vos données</li>
                </ul>

                <h4 class="mt-3">Interdictions</h4>
                <ul>
                    <li>Partage des accès avec des tiers non autorisés</li>
                    <li>Installation de logiciels non approuvés</li>
                    <li>Utilisation à des fins personnelles</li>
                    <li>Surcharge intentionnelle des ressources</li>
                </ul>

                <h4 class="mt-3">Sanctions</h4>
                <p>Le non-respect des règles peut entraîner:</p>
                <ul>
                    <li>Suspension temporaire de l'accès</li>
                    <li>Révocation du compte utilisateur</li>
                    <li>Signalement aux autorités compétentes</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Support Technique</h3>
            </div>
            <div class="card-body">
                <h4>En cas de Problème</h4>
                <p>Si vous rencontrez un problème technique:</p>
                <ol>
                    <li>Connectez-vous à votre compte</li>
                    <li>Accédez à la section "Incidents"</li>
                    <li>Signalez le problème en détaillant:
                        <ul>
                            <li>La ressource concernée</li>
                            <li>La nature du problème</li>
                            <li>Les circonstances</li>
                        </ul>
                    </li>
                </ol>

                <h4 class="mt-3">Maintenance Planifiée</h4>
                <p>Des maintenances régulières sont effectuées pour garantir la qualité du service. Vous serez notifié à l'avance en cas de maintenance affectant vos réservations.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Commencer</h3>
            </div>
            <div class="card-body text-center">
                <p>Prêt à utiliser nos ressources?</p>
                <div class="flex gap-2">
                    <a href="{{ route('account-request.create') }}" class="btn btn-primary">
                        Demander un compte
                    </a>
                    <a href="{{ route('guest.resources') }}" class="btn btn-outline">
                        Voir les ressources
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
