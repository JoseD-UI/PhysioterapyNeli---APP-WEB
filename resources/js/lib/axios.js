import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true, // Importante para cookie Sanctum/CSRF
    withXSRFToken: true    // Laravel 11+ CSRF handling
});

// Request Interceptor: Inyectar Token
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Response Interceptor: Manejo global de errores
api.interceptors.response.use(
    (response) => response,
    (error) => {
        const status = error.response ? error.response.status : null;

        if (status === 401) {
            // Usuario no autenticado o sesión expirada
            console.warn('Sesión expirada o no autenticado.');
            
            // Evitar loop infinito si ya estamos en login
            // TAMBIÉN evitamos redirigir si el usuario está en la landing page ("/")
            // o en la página de reseteo de password ("/password-reset")
            // para que no le salga el login automáticamente.
            const path = window.location.pathname;
            if (path !== '/login' && path !== '/' && !path.startsWith('/password-reset')) {
                 console.warn('Redirigiendo a login...');
                 window.location.href = '/login';
            }
        }

        if (status === 403) {
            console.error('Acceso denegado: No tienes permisos suficientes.');
        }
        
        if (status === 419) {
            console.error('CSRF Token Mismatch. Refrescando página...');
            // window.location.reload(); // Opcional: recargar para obtener nuevo token
        }

        return Promise.reject(error);
    }
);

export default api;
