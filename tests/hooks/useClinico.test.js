import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import {
    useGetHistorias,
    useCreateHistoria,
    useDeleteHistoria,
    useGetSesiones,
    useCreateSesion,
} from '../../resources/js/hooks/useClinico';

vi.mock('../../resources/js/lib/axios', () => ({
    default: {
        get: vi.fn(),
        post: vi.fn(),
        put: vi.fn(),
        delete: vi.fn(),
    }
}));

const createWrapper = () => {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
            mutations: { retry: false },
        },
    });
    return ({ children }) => (
        <QueryClientProvider client={queryClient}>
            {children}
        </QueryClientProvider>
    );
};

describe('useClinico hooks', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    // Historias
    it('should render useGetHistorias hook', () => {
        const { result } = renderHook(() => useGetHistorias(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.isLoading).toBeDefined();
    });

    it('should render useCreateHistoria hook', () => {
        const { result } = renderHook(() => useCreateHistoria(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.mutate).toBeDefined();
    });

    it('should render useDeleteHistoria hook', () => {
        const { result } = renderHook(() => useDeleteHistoria(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.mutate).toBeDefined();
    });

    // Sesiones
    it('should render useGetSesiones hook', () => {
        const { result } = renderHook(() => useGetSesiones(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.isLoading).toBeDefined();
    });

    it('should render useCreateSesion hook', () => {
        const { result } = renderHook(() => useCreateSesion(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.mutate).toBeDefined();
    });
});
