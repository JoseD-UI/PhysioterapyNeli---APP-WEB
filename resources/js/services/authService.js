import api from '../lib/axios';

export const authService = {
    /**
     * Obtener cookie CSRF (necesario para Sanctum primera vez)
     */
    csrf: () => api.get('/sanctum/csrf-cookie'),

    /**
     * Login con Email y Password
     * @param {string} email 
     * @param {string} password 
     */
    login: async (email, password) => {
        await authService.csrf();
        const response = await api.post('/auth/login', { email, password });
        return response.data;
    },

    /**
     * Login con Google (Híbrido)
     * @param {string} accessToken Token de acceso de Google
     */
    loginWithGoogle: async (accessToken, extraData = {}) => {
        await authService.csrf();
        const response = await api.post('/auth/google', { 
            access_token: accessToken,
            ...extraData
        });
        return response.data;
    },

    /**
     * Registro de usuario nuevo
     */
    register: async (userData) => {
        await authService.csrf();
        const response = await api.post('/auth/register', userData);
        return response.data;
    },

    /**
     * Cerrar sesión
     */
    logout: async () => {
        return api.post('/auth/logout');
    },

    /**
     * Obtener usuario actual y permisos
     */
    me: async () => {
        const response = await api.get('/auth/me');
        return response.data;
    },

    /**
     * Enviar correo de recuperación
     */
    forgotPassword: async (email) => {
        await authService.csrf();
        const response = await api.post('/auth/forgot-password', { email });
        return response.data;
    },

    /**
     * Restablecer contraseña con token
     */
    resetPassword: async ({ email, token, password, password_confirmation }) => {
        await authService.csrf();
        const response = await api.post('/auth/reset-password', { 
            email, token, password, password_confirmation 
        });
        return response.data;
    }
};
