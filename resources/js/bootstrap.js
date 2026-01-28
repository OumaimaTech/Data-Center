/**
 * Configuration Bootstrap JavaScript
 * Version: 2.0 - 100% Vanilla JavaScript
 */

// Configuration globale pour les requêtes AJAX natives
window.fetchConfig = {
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    credentials: 'same-origin'
};

// Helper pour les requêtes AJAX avec fetch API native
window.ajax = {
    get: async (url) => {
        const response = await fetch(url, {
            method: 'GET',
            ...window.fetchConfig
        });
        return response.json();
    },
    
    post: async (url, data) => {
        const response = await fetch(url, {
            method: 'POST',
            ...window.fetchConfig,
            body: JSON.stringify(data)
        });
        return response.json();
    },
    
    put: async (url, data) => {
        const response = await fetch(url, {
            method: 'PUT',
            ...window.fetchConfig,
            body: JSON.stringify(data)
        });
        return response.json();
    },
    
    delete: async (url) => {
        const response = await fetch(url, {
            method: 'DELETE',
            ...window.fetchConfig
        });
        return response.json();
    }
};
