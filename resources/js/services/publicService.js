import api from '../lib/axios';

export const publicService = {
    /**
     * Obtener listado de servicios públicos
     */
    getServices: async () => {
        const response = await api.get('/public/services');
        return response.data.data;
    }
};
