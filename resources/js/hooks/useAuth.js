import { useAuthStore } from '../stores/authStore';
import { useEffect } from 'react';

export function useAuth() {
    const store = useAuthStore();

    // Alias para propiedades comunes
    return {
        user: store.user,
        isAuthenticated: store.isAuthenticated,
        isLoading: store.isLoading,
        error: store.error,
        login: store.login,
        loginGoogle: store.loginGoogle,
        logout: store.logout,
        checkAuth: store.checkAuth,
        can: store.can
    };
}
