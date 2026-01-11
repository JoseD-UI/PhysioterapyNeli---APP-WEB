import api from '../lib/axios';

/**
 * Servicio de integración API para Principal (Personas, Usuarios)
 * Necesario para obtener lista de pacientes, fisioterapeutas, etc.
 */
export const principalService = {
    /**
     * Listar personas con filtros
     * @param {Object} filters - { tipo, estado, busqueda }
     */
    getPersonas: async (filters = {}) => {
        const response = await api.get('/principal/personas', { params: filters });
        return response.data;
    },

    /**
     * Obtener detalle de persona
     * @param {string|number} id - ID de la persona
     */
    getPersona: async (id) => {
        const response = await api.get(`/principal/personas/${id}`);
        return response.data;
    },

    /**
     * Crear persona
     * @param {Object} data
     */
    createPersona: async (data) => {
        const response = await api.post('/principal/personas', data);
        return response.data;
    },

    /**
     * Actualizar persona
     * @param {string|number} id
     * @param {Object} data
     */
    updatePersona: async (id, data) => {
        const response = await api.put(`/principal/personas/${id}`, data);
        return response.data;
    },

    /**
     * Eliminar persona
     * @param {string|number} id
     */
    deletePersona: async (id) => {
        const response = await api.delete(`/principal/personas/${id}`);
        return response.data;
    },

    /**
     * Listar usuarios
     * @param {Object} filters
     */
    getUsuarios: async (filters = {}) => {
        const response = await api.get('/principal/usuarios', { params: filters });
        return response.data;
    },

    /**
     * Obtener usuario por ID
     * @param {string|number} id
     */
    getUsuario: async (id) => {
        const response = await api.get(`/principal/usuarios/${id}`);
        return response.data;
    },

    /**
     * Listar salas
     */
    getSalas: async () => {
        const response = await api.get('/principal/salas');
        return response.data;
    },

    /**
     * Obtener sala por ID
     * @param {string|number} id
     */
    getSala: async (id) => {
        const response = await api.get(`/principal/salas/${id}`);
        return response.data;
    }
};
