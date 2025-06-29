import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add CSRF token configuration for axios
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Function to refresh CSRF token
window.refreshCSRFToken = function() {
    return fetch('/sanctum/csrf-cookie', {
        method: 'GET',
        credentials: 'same-origin'
    }).then(() => {
        // Update the meta tag with new token
        const newToken = document.head.querySelector('meta[name="csrf-token"]');
        if (newToken && window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newToken.content;
        }
    }).catch(error => {
        console.warn('Failed to refresh CSRF token:', error);
    });
};

// Configure global fetch to include CSRF token by default
const originalFetch = window.fetch;
window.fetch = function(url, options = {}) {
    // Only add CSRF token for non-GET requests
    if (!options.method || options.method.toUpperCase() !== 'GET') {
        options.headers = options.headers || {};
        
        // Add CSRF token if not already present
        if (!options.headers['X-CSRF-TOKEN'] && token) {
            options.headers['X-CSRF-TOKEN'] = token.content;
        }
    }
    
    return originalFetch(url, options).then(response => {
        // Handle 419 CSRF token mismatch
        if (response.status === 419 && !options._retried) {
            return window.refreshCSRFToken().then(() => {
                options._retried = true;
                const newToken = document.head.querySelector('meta[name="csrf-token"]');
                if (newToken) {
                    options.headers['X-CSRF-TOKEN'] = newToken.content;
                }
                return originalFetch(url, options);
            });
        }
        return response;
    });
};
