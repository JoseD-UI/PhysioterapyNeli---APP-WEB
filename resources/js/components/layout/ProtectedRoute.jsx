import React, { useEffect } from 'react';
import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { Loader2 } from 'lucide-react';

export default function ProtectedRoute({ requiredPermission }) {
    const { isAuthenticated, isLoading, checkAuth, can } = useAuth();
    const location = useLocation();

    // Al montar, verificamos sesión si aún está cargando
    useEffect(() => {
        checkAuth();
    }, []);

    if (isLoading) {
        return (
            <div className="h-screen w-full flex items-center justify-center bg-gray-50">
                <Loader2 className="h-8 w-8 animate-spin text-blue-600" />
                <span className="ml-2 text-gray-500 font-medium">Verificando sesión...</span>
            </div>
        );
    }

    // 1. Si no está autenticado, redirigir a Login
    if (!isAuthenticated) {
        // Guardamos la ruta intentada para redirigir después del login (TODO: Implementar esta lógica en Login)
        return <Navigate to="/login" state={{ from: location }} replace />;
    }

    // 2. Si requiere un permiso específico y no lo tiene
    if (requiredPermission && !can(requiredPermission)) {
        return (
            <div className="h-screen flex items-center justify-center flex-col">
                <h1 className="text-4xl font-bold text-gray-300 mb-4">403</h1>
                <p className="text-gray-600 text-lg">No tienes permisos para acceder a esta sección.</p>
                <div className="mt-4 p-4 bg-gray-100 rounded text-sm text-gray-500 font-mono">
                    Permiso requerido: {requiredPermission}
                </div>
            </div>
        );
    }

    // 3. Todo OK
    return <Outlet />;
}
