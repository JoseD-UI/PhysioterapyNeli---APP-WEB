import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { useGetCitas, useCreateCita, useDeleteCita } from '../../resources/js/hooks/useAgenda';

// Mock de axios/api
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

describe('useAgenda hooks', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should render useGetCitas hook', () => {
        const { result } = renderHook(() => useGetCitas(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.isLoading).toBeDefined();
    });

    it('should render useCreateCita hook', () => {
        const { result } = renderHook(() => useCreateCita(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.mutate).toBeDefined();
    });

    it('should render useDeleteCita hook', () => {
        const { result } = renderHook(() => useDeleteCita(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.mutate).toBeDefined();
    });
});
