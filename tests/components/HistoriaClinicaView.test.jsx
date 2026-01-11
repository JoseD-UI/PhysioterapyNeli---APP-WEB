import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { ThemeProvider } from '../../resources/js/components/theme-provider';
import HistoriaClinicaView from '../../resources/js/features/Clinico/HistoriaClinicaView';

vi.mock('../../resources/js/hooks/useClinico', () => ({
    useGetHistorias: () => ({
        data: {
            data: [
                {
                    id: 1,
                    paciente: { nombre: 'María García' },
                    diagnostico: 'Lumbalgia crónica',
                    anamnesis: 'Dolor en espalda baja',
                    observaciones: 'Requiere seguimiento',
                    created_at: '2026-01-10T10:00:00',
                }
            ]
        },
        isLoading: false,
        error: null,
    }),
    useCreateHistoria: () => ({
        mutate: vi.fn(),
        isPending: false,
    }),
    useUpdateHistoria: () => ({
        mutate: vi.fn(),
        isPending: false,
    }),
    useDeleteHistoria: () => ({
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

describe('HistoriaClinicaView Component', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('should render list of historias', () => {
        renderWithProviders(<HistoriaClinicaView />);
        
        expect(screen.getByText('María García')).toBeInTheDocument();
        expect(screen.getByText('Lumbalgia crónica')).toBeInTheDocument();
    });

    it('should display title', () => {
        renderWithProviders(<HistoriaClinicaView />);
        
        expect(screen.getByText('Historias Clínicas')).toBeInTheDocument();
    });

    it('should have new historia button', () => {
        renderWithProviders(<HistoriaClinicaView />);
        
        expect(screen.getByText('Nueva Historia')).toBeInTheDocument();
    });

    it('should display edit and delete buttons', () => {
        renderWithProviders(<HistoriaClinicaView />);
        
        const editButtons = screen.getAllByText('Editar');
        const deleteButtons = screen.getAllByText('Eliminar');
        
        expect(editButtons.length).toBeGreaterThan(0);
        expect(deleteButtons.length).toBeGreaterThan(0);
    });

    it('should display creation date', () => {
        renderWithProviders(<HistoriaClinicaView />);
        
        expect(screen.getByText(/Creado:/)).toBeInTheDocument();
    });
});
