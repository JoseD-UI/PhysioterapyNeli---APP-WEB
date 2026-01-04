import { create } from 'zustand';
import { authService } from '../services/authService';

export const useAuthStore = create((set, get) => ({
    user: null, // Objeto usuario completo
    isAuthenticated: false,
    isLoading: true, // Para mostrar loading inicial al verificar sesión
    error: null,

    /**
     * Inicializar Auth (verificar si hay sesión activa al cargar F5)
     */
    checkAuth: async () => {
        set({ isLoading: true });
        try {
            // Intentamos obtener el usuario. Si falla (401), el interceptor de Axios ya maneja parte,
            // pero aquí capturamos para limpiar estado.
            const data = await authService.me();
            // Aseguramos que data.user es lo que guardamos
            set({ user: data.user, isAuthenticated: true, isLoading: false, error: null });
        } catch (error) {
            // Si falla, asumimos no autenticado (token expirado o inválido)
            // Si falla, asumimos no autenticado; es normal para visitantes.
            // console.debug('Sesión no activa (Guest)');
            set({ user: null, isAuthenticated: false, isLoading: false });
        }
    },

    login: async (email, password) => {
        set({ isLoading: true, error: null });
        try {
            const data = await authService.login(email, password);
             // Guardar token si viene en la respuesta
             if (data.access_token) {
                 localStorage.setItem('auth_token', data.access_token);
             }
             
             // Después de login, obtenemos datos completos
             await get().checkAuth();
             return true; 
        } catch (error) {
            set({ 
                error: error.response?.data?.message || 'Error al iniciar sesión',
                isLoading: false 
            });
            throw error;
        }
    },

    loginGoogle: async (accessToken) => {
        set({ isLoading: true, error: null });
        try {
            const data = await authService.loginWithGoogle(accessToken);
            if (data.access_token) {
                localStorage.setItem('auth_token', data.access_token);
            }
            await get().checkAuth();
            return true;
        } catch (error) {
            set({
                error: error.response?.data?.message || 'Error con Google Login',
                isLoading: false
            });
            throw error;
        }
    },

    logout: async () => {
        set({ isLoading: true });
        try {
            await authService.logout();
        } catch (error) {
            console.error('Logout error', error);
        } finally {
            localStorage.removeItem('auth_token');
            set({ user: null, isAuthenticated: false, isLoading: false });
            // Redirigir a inicio (landing pública)
            window.location.href = '/';
        }
    },

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    can: (permissionCode) => {
        const { user } = get();
        if (!user || !user.usuario_principal) return false;
        
        // Si es admin, tiene todo
        if (user.usuario_principal.rol?.nombre === 'ADMINISTRADOR') return true;

        const permisos = user.usuario_principal.permisos || [];
        return permisos.includes(permissionCode);
    }
}));
