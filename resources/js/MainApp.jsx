import React from 'react';
import { BrowserRouter } from 'react-router-dom';
import { QueryClientProvider } from '@tanstack/react-query';
import { queryClient } from './lib/queryClient';
import AppRoutes from './routes/AppRoutes';

import { GoogleOAuthProvider } from '@react-oauth/google';

// ID de cliente obtenido de variables de entorno (Vite)
import { useRef } from 'react';
import { ThemeProvider } from './components/theme-provider';

// ID de cliente obtenido de variables de entorno (Vite)
const GOOGLE_CLIENT_ID = import.meta.env.VITE_GOOGLE_CLIENT_ID || "TU_GOOGLE_CLIENT_ID_AQUI";

// Importación dinámica para evitar ciclos, o estática si es segua
import CompleteProfileModal from './components/auth/CompleteProfileModal';
import { ThemeToggle } from './components/ui/ThemeToggle';
import { useAuth } from './hooks/useAuth';

export default function MainApp() {
    const { isAuthenticated } = useAuth(); // Hook básico para re-render

    // Inicializar sesión al cargar la app
    React.useEffect(() => {
        // Importación dinámica o acceso directo al store si es posible
        import('./stores/authStore').then(({ useAuthStore }) => {
            useAuthStore.getState().checkAuth();
        });
    }, []);

    return (
        <GoogleOAuthProvider clientId={GOOGLE_CLIENT_ID}>
            <ThemeProvider defaultTheme="light" storageKey="vite-ui-theme">
                <QueryClientProvider client={queryClient}>
                    <BrowserRouter>
                        {/* Modal Global de Completado de Perfil */}
                        <CompleteProfileModal />
                        <ThemeToggle />
                        <AppRoutes />
                    </BrowserRouter>
                </QueryClientProvider>
            </ThemeProvider>
        </GoogleOAuthProvider>
    );
}
