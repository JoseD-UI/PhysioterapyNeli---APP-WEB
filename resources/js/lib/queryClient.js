import { QueryClient } from '@tanstack/react-query';

export const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: 1, // Reintentar 1 vez si falla la petición
            staleTime: 1000 * 60 * 5, // Datos considerados frescos por 5 minutos
            refetchOnWindowFocus: false, // No recargar al cambiar de ventana (mejor UX en formularios)
        },
    },
});
