import { useQuery } from '@tanstack/react-query';
import { principalService } from '../services/principalService';

/**
 * Hook para obtener lista de personas
 */
export function useGetPersonas(filters = {}) {
    return useQuery({
        queryKey: ['personas', filters],
        queryFn: () => principalService.getPersonas(filters),
        staleTime: 5 * 60 * 1000, // 5 minutos
    });
}

/**
 * Hook para obtener personas de un tipo específico (pacientes, fisioterapeutas, etc.)
 */
export function useGetPersonasPorTipo(tipo) {
    return useQuery({
        queryKey: ['personas', { tipo }],
        queryFn: () => principalService.getPersonas({ tipo }),
        enabled: !!tipo,
        staleTime: 5 * 60 * 1000,
    });
}

/**
 * Hook para obtener detalle de una persona
 */
export function useGetPersona(id) {
    return useQuery({
        queryKey: ['persona', id],
        queryFn: () => principalService.getPersona(id),
        enabled: !!id,
        staleTime: 5 * 60 * 1000,
    });
}

/**
 * Hook para obtener lista de salas
 */
export function useGetSalas() {
    return useQuery({
        queryKey: ['salas'],
        queryFn: () => principalService.getSalas(),
        staleTime: 60 * 60 * 1000, // 1 hora
    });
}

/**
 * Hook para obtener lista de usuarios
 */
export function useGetUsuarios(filters = {}) {
    return useQuery({
        queryKey: ['usuarios', filters],
        queryFn: () => principalService.getUsuarios(filters),
        staleTime: 5 * 60 * 1000,
    });
}

/**
 * Hook para obtener lista de fisioterapeutas
 */
export function useGetFisioterapeutas(filters = {}) {
    return useQuery({
        queryKey: ['fisioterapeutas', filters],
        queryFn: () => principalService.getPersonas({ tipo: 'fisioterapeuta', ...filters }),
        staleTime: 5 * 60 * 1000,
    });
}
