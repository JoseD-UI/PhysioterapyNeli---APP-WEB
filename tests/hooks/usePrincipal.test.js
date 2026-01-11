import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import {
    useGetPersonas,
    useGetPersonasPorTipo,
    useGetSalas,
} from '../../resources/js/hooks/usePrincipal';

vi.mock('../../resources/js/lib/axios', () => ({
    default: {
        get: vi.fn(),
    }
}));

const createWrapper = () => {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { retry: false },
        },
    });
    return ({ children }) => (
        <QueryClientProvider client={queryClient}>
            {children}
        </QueryClientProvider>
    );
};

describe('usePrincipal hooks', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should render useGetPersonas hook', () => {
        const { result } = renderHook(() => useGetPersonas(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.isLoading).toBeDefined();
    });

    it('should render useGetPersonasPorTipo hook', () => {
        const { result } = renderHook(() => useGetPersonasPorTipo('paciente'), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.isLoading).toBeDefined();
    });

    it('should render useGetSalas hook', () => {
        const { result } = renderHook(() => useGetSalas(), {
            wrapper: createWrapper(),
        });

        expect(result.current).toBeDefined();
        expect(result.current.isLoading).toBeDefined();
    });

    it('should not fetch when tipo is not provided', () => {
        const { result } = renderHook(() => useGetPersonasPorTipo(null), {
            wrapper: createWrapper(),
        });

        expect(result.current.isLoading).toBe(false);
    });
});
