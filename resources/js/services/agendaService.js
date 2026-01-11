import api from '../lib/axios';

/**
 * Servicio de integración API para Agenda (Citas)
 */
export const agendaService = {
    /**
     * Listar todas las citas con filtros opcionales
     * @param {Object} filters - { estado, fecha_desde, fecha_hasta, fisioterapeuta_id, paciente_id }
     */
    getCitas: async (filters = {}) => {
        const response = await api.get('/agenda/citas', { params: filters });
        return response.data;
    },

    /**
     * Obtener detalle de una cita
     * @param {string|number} id - ID de la cita
     */
    getCita: async (id) => {
        const response = await api.get(`/agenda/citas/${id}`);
        return response.data;
    },

    /**
     * Crear nueva cita
     * @param {Object} data - { paciente_id, fisioterapeuta_id, fecha_hora, sala_id, tipo_servicio_id, notas }
     */
    createCita: async (data) => {
        const response = await api.post('/agenda/citas', data);
        return response.data;
    },

    /**
     * Actualizar cita
     * @param {string|number} id - ID de la cita
     * @param {Object} data - Campos a actualizar
     */
    updateCita: async (id, data) => {
        const response = await api.put(`/agenda/citas/${id}`, data);
        return response.data;
    },

    /**
     * Cancelar/Eliminar cita
     * @param {string|number} id - ID de la cita
     */
    deleteCita: async (id) => {
        const response = await api.delete(`/agenda/citas/${id}`);
        return response.data;
    },

    /**
     * Obtener horarios de un fisioterapeuta
     * @param {string|number} fisioterapeutaId
     * @param {Object} filters - { fecha_desde, fecha_hasta }
     */
    getHorarios: async (fisioterapeutaId, filters = {}) => {
        const response = await api.get(`/agenda/horarios/${fisioterapeutaId}`, { params: filters });
        return response.data;
    },

    /**
     * Obtener disponibilidad (espacio libre en calendario)
     * @param {string|number} fisioterapeutaId
     * @param {string} fecha - YYYY-MM-DD
     */
    getDisponibilidad: async (fisioterapeutaId, fecha) => {
        const response = await api.get('/agenda/disponibilidad', {
            params: { fisioterapeuta_id: fisioterapeutaId, fecha }
        });
        return response.data;
    },

    /**
     * Obtener días no laborables
     */
    getDiasNoLaborables: async () => {
        const response = await api.get('/agenda/dias-no-laborables');
        return response.data;
    },

    /**
     * Cambiar estado de cita (confirmada, cancelada, completada)
     * @param {string|number} id - ID de la cita
     * @param {string} estado - Nuevo estado
     */
    cambiarEstadoCita: async (id, estado) => {
        const response = await api.put(`/agenda/citas/${id}/estado`, { estado });
        return response.data;
    },

    /**
     * Reprogramar cita (cambiar fecha/hora)
     * @param {string|number} id - ID de la cita
     * @param {Object} data - { fecha_hora, sala_id }
     */
    reprogramarCita: async (id, data) => {
        const response = await api.put(`/agenda/citas/${id}/reprogramar`, data);
        return response.data;
    }
};
