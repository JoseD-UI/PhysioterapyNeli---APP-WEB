import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { ThemeProvider } from '../../resources/js/components/theme-provider';
import CitasListView from '../../resources/js/features/Agenda/CitasListView';

// Mock hooks
vi.mock('../../resources/js/hooks/useAgenda', () => ({
    useGetCitas: () => ({
        data: {
            data: [
                {
                    id: 1,
                    paciente: { nombre: 'Juan Pérez' },
                    fisioterapeuta: { nombre: 'Dr. Martínez' },
                    fecha_hora: '2026-01-15T10:00:00',
                    sala: { nombre: 'Sala A' },
                    tipo_servicio: { nombre: 'Masaje Terapéutico' },
                    estado: 'confirmada',
                    notas: 'Cita importante',
                }
            ]
        },
        isLoading: false,
        error: null,
    }),
    useCambiarEstadoCita: () => ({
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

describe('CitasListView Component', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should render list of citas', () => {
        renderWithProviders(<CitasListView />);
        
        expect(screen.getByText('Juan Pérez')).toBeInTheDocument();
        expect(screen.getByText('Masaje Terapéutico')).toBeInTheDocument();
    });

    it('should display filter controls', () => {
        renderWithProviders(<CitasListView />);
        
        expect(screen.getByText('Filtros')).toBeInTheDocument();
        expect(screen.getByDisplayValue('Todos')).toBeInTheDocument();
    });

    it('should display cita status badge', () => {
        renderWithProviders(<CitasListView />);
        
        expect(screen.getByText('Confirmada')).toBeInTheDocument();
    });

    it('should render action buttons', () => {
        renderWithProviders(<CitasListView />);
        
        expect(screen.getByText('Completar')).toBeInTheDocument();
    });
});
