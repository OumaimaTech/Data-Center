@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="mb-4">
    <h1>Centre de Notifications</h1>
    <p style="color: var(--secondary-color);">Gérez toutes vos notifications en un seul endroit</p>
</div>

<!-- Statistiques des notifications -->
<div class="grid grid-3 mb-4">
    <div class="stat-card">
        <h3>Total</h3>
        <div class="stat-value">{{ $notifications->total() }}</div>
        <p style="font-size: 0.875rem; color: var(--secondary-color);">notifications</p>
    </div>
    <div class="stat-card">
        <h3>Non lues</h3>
        <div class="stat-value">{{ $notifications->where('read_at', null)->count() }}</div>
        <p style="font-size: 0.875rem; color: var(--secondary-color);">à traiter</p>
    </div>
    <div class="stat-card">
        <h3>Lues</h3>
        <div class="stat-value">{{ $notifications->where('read_at', '!=', null)->count() }}</div>
        <p style="font-size: 0.875rem; color: var(--secondary-color);">traitées</p>
    </div>
</div>

<div class="card">
    <div class="flex-between" style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h2 style="margin: 0;">Toutes les Notifications</h2>
        @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>
    
    <div class="card-body">
        @if($notifications->count() > 0)
            <div class="notifications-list">
                @foreach($notifications as $notification)
                <div class="notification-card {{ $notification->read_at ? 'notification-read' : 'notification-unread' }} notification-{{ $notification->type }}">
                    <div class="notification-indicator"></div>
                    
                    <div class="notification-icon-wrapper">
                        @if($notification->type === 'success')
                            <div class="notification-icon success-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                        @elseif($notification->type === 'error')
                            <div class="notification-icon error-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                            </div>
                        @elseif($notification->type === 'warning')
                            <div class="notification-icon warning-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                        @else
                            <div class="notification-icon info-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="notification-content">
                        <div class="notification-header">
                            <h3 class="notification-title">{{ $notification->title }}</h3>
                            @if(!$notification->read_at)
                                <span class="badge badge-new">Nouveau</span>
                            @endif
                        </div>
                        <p class="notification-message">{{ $notification->message }}</p>
                        <div class="notification-meta">
                            <span class="notification-time">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            @if($notification->read_at)
                                <span class="notification-read-time">
                                    Lu {{ $notification->read_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="notification-actions">
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-icon" title="Marquer comme lu">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <h3>Aucune notification</h3>
                <p style="color: var(--secondary-color); margin-top: 0.5rem;">
                    Vous êtes à jour ! Aucune notification pour le moment.
                </p>
            </div>
        @endif
    </div>
</div>

<style>
/* Statistiques */
.stat-card h3 {
    margin: 0 0 0.5rem 0;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0.5rem 0;
}

/* Liste des notifications */
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-card {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.notification-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

/* Indicateur de statut */
.notification-indicator {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: transparent;
}

.notification-unread .notification-indicator {
    background: #10b981;
}

.notification-unread {
    background: #d1fae5;
    border-left: 4px solid #10b981;
}

/* Icônes de notification */
.notification-icon-wrapper {
    flex-shrink: 0;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.success-icon {
    background: #28a745;
}

.error-icon {
    background: #dc3545;
}

.warning-icon {
    background: #ffc107;
}

.info-icon {
    background: #17a2b8;
}

/* Contenu de la notification */
.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.notification-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color);
}

.badge-new {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #10b981;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.notification-message {
    margin: 0 0 0.75rem 0;
    color: var(--secondary-color);
    line-height: 1.6;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.notification-time,
.notification-read-time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.875rem;
    color: #999;
}

.notification-time svg {
    opacity: 0.7;
}

/* Actions */
.notification-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border: 1px solid var(--border-color);
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    color: var(--secondary-color);
}

.btn-icon:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

.btn-icon-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 120px;
    height: 120px;
    background: #e9ecef;
    border-radius: 50%;
    margin-bottom: 1.5rem;
    color: var(--secondary-color);
}

.empty-state h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
    color: var(--text-color);
}

/* Responsive */
@media (max-width: 768px) {
    .notification-card {
        flex-direction: column;
        padding: 1rem;
    }
    
    .notification-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .stat-value {
        font-size: 2rem;
    }
}
</style>
@endsection
