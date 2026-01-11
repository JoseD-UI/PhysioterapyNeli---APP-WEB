import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { clinicoService } from '../services/clinicoService';

// ==================== TIPOS DE SERVICIO ====================

export function useGetTiposServicio() {
    return useQuery({
        queryKey: ['tipos-servicio'],
        queryFn: () => clinicoService.getTiposServicio(),
        staleTime: 60 * 60 * 1000, // 1 hora
    });
}

export function useGetTipoServicio(id) {
    return useQuery({
        queryKey: ['tipo-servicio', id],
        queryFn: () => clinicoService.getTipoServicio(id),
        enabled: !!id,
        staleTime: 60 * 60 * 1000,
    });
}

export function useCreateTipoServicio() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data) => clinicoService.createTipoServicio(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['tipos-servicio'] });
        },
    });
}

export function useUpdateTipoServicio() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }) => clinicoService.updateTipoServicio(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['tipos-servicio'] });
        },
    });
}

export function useDeleteTipoServicio() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id) => clinicoService.deleteTipoServicio(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['tipos-servicio'] });
        },
    });
}

// ==================== HISTORIAS CLÍNICAS ====================

export function useGetHistorias(filters = {}) {
    return useQuery({
        queryKey: ['historias', filters],
        queryFn: () => clinicoService.getHistorias(filters),
        staleTime: 5 * 60 * 1000, // 5 minutos
    });
}

export function useGetHistoria(id) {
    return useQuery({
        queryKey: ['historia', id],
        queryFn: () => clinicoService.getHistoria(id),
        enabled: !!id,
        staleTime: 5 * 60 * 1000,
    });
}

export function useCreateHistoria() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data) => clinicoService.createHistoria(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['historias'] });
        },
    });
}

export function useUpdateHistoria() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }) => clinicoService.updateHistoria(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['historias'] });
        },
    });
}

export function useDeleteHistoria() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id) => clinicoService.deleteHistoria(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['historias'] });
        },
    });
}

// ==================== SESIONES ====================

export function useGetSesiones(filters = {}) {
    return useQuery({
        queryKey: ['sesiones', filters],
        queryFn: () => clinicoService.getSesiones(filters),
        staleTime: 5 * 60 * 1000, // 5 minutos
    });
}

export function useGetSesion(id) {
    return useQuery({
        queryKey: ['sesion', id],
        queryFn: () => clinicoService.getSesion(id),
        enabled: !!id,
        staleTime: 5 * 60 * 1000,
    });
}

export function useCreateSesion() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data) => clinicoService.createSesion(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['sesiones'] });
        },
    });
}

export function useUpdateSesion() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }) => clinicoService.updateSesion(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['sesiones'] });
        },
    });
}

export function useDeleteSesion() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id) => clinicoService.deleteSesion(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['sesiones'] });
        },
    });
}

export function useCompletarSesion() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }) => clinicoService.completarSesion(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['sesiones'] });
        },
    });
}
