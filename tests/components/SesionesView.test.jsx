import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { ThemeProvider } from '../../resources/js/components/theme-provider';
import SesionesView from '../../resources/js/features/Clinico/SesionesView';

vi.mock('../../resources/js/hooks/useClinico', () => ({
    useGetSesiones: () => ({
        data: {
            data: [
                {
                    id: 1,
                    cita: {
                        paciente: { nombre: 'Carlos López' },
                        fisioterapeuta: { nombre: 'Dra. Rodríguez' },
                    },
                    estado: 'completada',
                    notas_clinicas: 'Sesión exitosa',
                    created_at: '2026-01-10T10:00:00',
                }
            ]
        },
        isLoading: false,
        error: null,
    }),
    useCreateSesion: () => ({
        mutate: vi.fn(),
        isPending: false,
    }),
    useUpdateSesion: () => ({
        mutate: vi.fn(),
        isPending: false,
    }),
    useDeleteSesion: () => ({
        mutate: vi.fn(),
        isPending: false,
    }),
    useCompletarSesion: () => ({
        mutate: vi.fn(),
        isPending: false,
    }),
}));

const queryClient = new QueryClient({
    defaultOptions: {
        queries: { retry: false },
    },
});

const renderWithProviders = (component) => {
    return render(
        <QueryClientProvider client={queryClient}>
            <ThemeProvider>
                {component}
            </ThemeProvider>
        </QueryClientProvider>
    );
};

describe('SesionesView Component', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should render list of sesiones', () => {
        renderWithProviders(<SesionesView />);
        
        expect(screen.getByText('Carlos López')).toBeInTheDocument();
        expect(screen.getByText('Dra. Rodríguez')).toBeInTheDocument();
    });

    it('should display title', () => {
        renderWithProviders(<SesionesView />);
        
        expect(screen.getByText('Sesiones de Tratamiento')).toBeInTheDocument();
    });

    it('should have new sesion button', () => {
        renderWithProviders(<SesionesView />);
        
        expect(screen.getByText('Nueva Sesión')).toBeInTheDocument();
    });

    it('should display estado badge', () => {
        renderWithProviders(<SesionesView />);
        
        expect(screen.getByText('Completada')).toBeInTheDocument();
    });

    it('should display clinical notes', () => {
        renderWithProviders(<SesionesView />);
        
        expect(screen.getByText(/Notas Clínicas:/)).toBeInTheDocument();
        expect(screen.getByText('Sesión exitosa')).toBeInTheDocument();
    });

    it('should have action buttons', () => {
        renderWithProviders(<SesionesView />);
        
        const editButtons = screen.getAllByText('Editar');
        const deleteButtons = screen.getAllByText('Eliminar');
        
        expect(editButtons.length).toBeGreaterThan(0);
        expect(deleteButtons.length).toBeGreaterThan(0);
    });
});
