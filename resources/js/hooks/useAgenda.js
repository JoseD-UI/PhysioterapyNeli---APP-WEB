import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { agendaService } from '../services/agendaService';
import { useState } from 'react';

/**
 * Hook para obtener lista de citas
 */
export function useGetCitas(filters = {}) {
    return useQuery({
        queryKey: ['citas', filters],
        queryFn: () => agendaService.getCitas(filters),
        staleTime: 5 * 60 * 1000, // 5 minutos
    });
}

/**
 * Hook para obtener detalle de una cita
 */
export function useGetCita(id) {
    return useQuery({
        queryKey: ['cita', id],
        queryFn: () => agendaService.getCita(id),
        enabled: !!id,
        staleTime: 5 * 60 * 1000,
    });
}

/**
 * Hook para crear cita
 */
export function useCreateCita() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (data) => agendaService.createCita(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['citas'] });
        },
    });
}

/**
 * Hook para actualizar cita
 */
export function useUpdateCita() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }) => agendaService.updateCita(id, data),
        onSuccess: (data) => {
            queryClient.invalidateQueries({ queryKey: ['citas'] });
            queryClient.setQueryData(['cita', data.data?.id], data);
        },
    });
}

/**
 * Hook para eliminar/cancelar cita
 */
export function useDeleteCita() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (id) => agendaService.deleteCita(id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['citas'] });
        },
    });
}

/**
 * Hook para cambiar estado de cita
 */
export function useCambiarEstadoCita() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, estado }) => agendaService.cambiarEstadoCita(id, estado),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['citas'] });
        },
    });
}

/**
 * Hook para reprogramar cita
 */
export function useReprogramarCita() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: ({ id, data }) => agendaService.reprogramarCita(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['citas'] });
        },
    });
}

/**
 * Hook para obtener horarios disponibles
 */
export function useGetDisponibilidad(fisioterapeutaId, fecha) {
    return useQuery({
        queryKey: ['disponibilidad', fisioterapeutaId, fecha],
        queryFn: () => agendaService.getDisponibilidad(fisioterapeutaId, fecha),
        enabled: !!fisioterapeutaId && !!fecha,
        staleTime: 2 * 60 * 1000, // 2 minutos
    });
}

/**
 * Hook para obtener días no laborables
 */
export function useGetDiasNoLaborables() {
    return useQuery({
        queryKey: ['dias-no-laborables'],
        queryFn: () => agendaService.getDiasNoLaborables(),
        staleTime: 24 * 60 * 60 * 1000, // 24 horas
    });
}
