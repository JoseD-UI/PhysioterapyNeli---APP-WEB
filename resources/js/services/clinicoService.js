import api from '../lib/axios';

/**
 * Servicio de integración API para Clínico (Historia Clínica, Sesiones, Tipos de Servicio)
 */
export const clinicoService = {
    // ==================== TIPOS DE SERVICIO ====================

    /**
     * Listar todos los tipos de servicio
     */
    getTiposServicio: async () => {
        const response = await api.get('/clinico/tipos-servicio');
        return response.data;
    },

    /**
     * Obtener detalle de tipo de servicio
     * @param {string|number} id
     */
    getTipoServicio: async (id) => {
        const response = await api.get(`/clinico/tipos-servicio/${id}`);
        return response.data;
    },

    /**
     * Crear tipo de servicio
     * @param {Object} data - { nombre, descripcion, precio, duracion_minutos }
     */
    createTipoServicio: async (data) => {
        const response = await api.post('/clinico/tipos-servicio', data);
        return response.data;
    },

    /**
     * Actualizar tipo de servicio
     * @param {string|number} id
     * @param {Object} data
     */
    updateTipoServicio: async (id, data) => {
        const response = await api.put(`/clinico/tipos-servicio/${id}`, data);
        return response.data;
    },

    /**
     * Eliminar tipo de servicio
     * @param {string|number} id
     */
    deleteTipoServicio: async (id) => {
        const response = await api.delete(`/clinico/tipos-servicio/${id}`);
        return response.data;
    },

    // ==================== HISTORIAS CLÍNICAS ====================

    /**
     * Listar historias clínicas con filtros
     * @param {Object} filters - { paciente_id, estado }
     */
    getHistorias: async (filters = {}) => {
        const response = await api.get('/clinico/historias', { params: filters });
        return response.data;
    },

    /**
     * Obtener detalle de historia clínica
     * @param {string|number} id
     */
    getHistoria: async (id) => {
        const response = await api.get(`/clinico/historias/${id}`);
        return response.data;
    },

    /**
     * Crear historia clínica
     * @param {Object} data - { paciente_id, diagnostico, anamnesis, observaciones }
     */
    createHistoria: async (data) => {
        const response = await api.post('/clinico/historias', data);
        return response.data;
    },

    /**
     * Actualizar historia clínica
     * @param {string|number} id
     * @param {Object} data
     */
    updateHistoria: async (id, data) => {
        const response = await api.put(`/clinico/historias/${id}`, data);
        return response.data;
    },

    /**
     * Eliminar historia clínica
     * @param {string|number} id
     */
    deleteHistoria: async (id) => {
        const response = await api.delete(`/clinico/historias/${id}`);
        return response.data;
    },

    // ==================== SESIONES ====================

    /**
     * Listar sesiones con filtros
     * @param {Object} filters - { paciente_id, fisioterapeuta_id, fecha_desde, fecha_hasta, estado }
     */
    getSesiones: async (filters = {}) => {
        const response = await api.get('/clinico/sesiones', { params: filters });
        return response.data;
    },

    /**
     * Obtener detalle de sesión
     * @param {string|number} id
     */
    getSesion: async (id) => {
        const response = await api.get(`/clinico/sesiones/${id}`);
        return response.data;
    },

    /**
     * Crear sesión
     * @param {Object} data - { cita_id, observaciones, notas_clinicas, resultados, proximas_recomendaciones }
     */
    createSesion: async (data) => {
        const response = await api.post('/clinico/sesiones', data);
        return response.data;
    },

    /**
     * Actualizar sesión
     * @param {string|number} id
     * @param {Object} data
     */
    updateSesion: async (id, data) => {
        const response = await api.put(`/clinico/sesiones/${id}`, data);
        return response.data;
    },

    /**
     * Eliminar sesión
     * @param {string|number} id
     */
    deleteSesion: async (id) => {
        const response = await api.delete(`/clinico/sesiones/${id}`);
        return response.data;
    },

    /**
     * Completar sesión (marcar como finalizada)
     * @param {string|number} id
     * @param {Object} data - Datos finales de la sesión
     */
    completarSesion: async (id, data) => {
        const response = await api.put(`/clinico/sesiones/${id}/completar`, data);
        return response.data;
    }
};
