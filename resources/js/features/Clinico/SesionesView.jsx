import React, { useState } from 'react';
import { Activity, Plus, AlertCircle, Loader2 } from 'lucide-react';
import { useGetSesiones, useCreateSesion, useUpdateSesion, useDeleteSesion, useCompletarSesion } from '../../hooks/useClinico';
import { useTheme } from '../../components/theme-provider';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import { Input } from '../../components/ui/Input';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';

export default function SesionesView() {
    const { theme } = useTheme();
    const [filters, setFilters] = useState({ estado: '' });
    const [isFormOpen, setIsFormOpen] = useState(false);
    const [selectedSesion, setSelectedSesion] = useState(null);
    const [formData, setFormData] = useState({
        cita_id: '',
        observaciones: '',
        notas_clinicas: '',
        resultados: '',
        proximas_recomendaciones: '',
    });

    const { data, isLoading, error } = useGetSesiones(filters);
    const { mutate: createSesion, isPending: isCreating } = useCreateSesion();
    const { mutate: updateSesion, isPending: isUpdating } = useUpdateSesion();
    const { mutate: deleteSesion, isPending: isDeleting } = useDeleteSesion();
    const { mutate: completarSesion, isPending: isCompleting } = useCompletarSesion();

    const sesiones = data?.data || [];
    const isLoading_mutation = isCreating || isUpdating || isDeleting || isCompleting;

    const handleNewSesion = () => {
        setSelectedSesion(null);
        setFormData({
            cita_id: '',
            observaciones: '',
            notas_clinicas: '',
            resultados: '',
            proximas_recomendaciones: '',
        });
        setIsFormOpen(true);
    };

    const handleEditSesion = (sesion) => {
        setSelectedSesion(sesion);
        setFormData({
            cita_id: sesion.cita_id,
            observaciones: sesion.observaciones,
            notas_clinicas: sesion.notas_clinicas,
            resultados: sesion.resultados,
            proximas_recomendaciones: sesion.proximas_recomendaciones,
        });
        setIsFormOpen(true);
    };

    const handleSubmitForm = (e) => {
        e.preventDefault();

        if (selectedSesion) {
            updateSesion(
                { id: selectedSesion.id, data: formData },
                { onSuccess: () => setIsFormOpen(false) }
            );
        } else {
            createSesion(formData, { onSuccess: () => setIsFormOpen(false) });
        }
    };

    const handleCompletarSesion = (id) => {
        completarSesion(
            { id, data: { estado: 'completada' } },
            { onSuccess: () => {} }
        );
    };

    const handleDeleteSesion = (id) => {
        if (window.confirm('¿Estás seguro de que deseas eliminar esta sesión?')) {
            deleteSesion(id);
        }
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center p-12">
                <Loader2 className="w-8 h-8 animate-spin text-blue-500" />
                <span className="ml-2 text-gray-600 dark:text-gray-400">Cargando sesiones...</span>
            </div>
        );
    }

    if (error) {
        return (
            <div className={`p-4 rounded-lg flex items-center gap-3 ${
                theme === 'dark' ? 'bg-red-900 text-red-200' : 'bg-red-50 text-red-800'
            }`}>
                <AlertCircle className="w-5 h-5" />
                <span>Error al cargar: {error.message}</span>
            </div>
        );
    }

    return (
        <div className={`space-y-6 ${theme === 'dark' ? 'dark' : ''}`}>
            {/* Header */}
            <div className="flex items-center justify-between mb-6">
                <h2 className={`text-2xl font-bold flex items-center gap-2 ${
                    theme === 'dark' ? 'text-white' : 'text-gray-900'
                }`}>
                    <Activity className="w-6 h-6 text-blue-500" />
                    Sesiones de Tratamiento
                </h2>
                <Button
                    onClick={handleNewSesion}
                    className="flex items-center gap-2 bg-blue-500 hover:bg-blue-600"
                >
                    <Plus className="w-4 h-4" />
                    Nueva Sesión
                </Button>
            </div>

            {/* Filtros */}
            <Card className={theme === 'dark' ? 'dark' : ''}>
                <div className="p-4">
                    <select
                        value={filters.estado}
                        onChange={(e) => setFilters({ estado: e.target.value })}
                        className={`px-3 py-2 rounded border ${
                            theme === 'dark'
                                ? 'bg-gray-700 border-gray-600 text-white'
                                : 'bg-white border-gray-300 text-gray-900'
                        }`}
                    >
                        <option value="">Todas las sesiones</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="completada">Completada</option>
                    </select>
                </div>
            </Card>

            {/* Lista */}
            {sesiones.length === 0 ? (
                <Card className={theme === 'dark' ? 'dark' : ''}>
                    <div className={`p-8 text-center ${
                        theme === 'dark' ? 'text-gray-400' : 'text-gray-500'
                    }`}>
                        <Activity className="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>No hay sesiones registradas</p>
                    </div>
                </Card>
            ) : (
                <div className="space-y-4">
                    {sesiones.map((sesion) => (
                        <Card key={sesion.id} className={theme === 'dark' ? 'dark' : ''}>
                            <div className="p-4 space-y-3">
                                {/* Encabezado */}
                                <div className="flex items-start justify-between">
                                    <div>
                                        <p className={`text-sm font-medium ${
                                            theme === 'dark' ? 'text-gray-400' : 'text-gray-600'
                                        }`}>
                                            Paciente
                                        </p>
                                        <h3 className={`font-bold text-lg ${
                                            theme === 'dark' ? 'text-white' : 'text-gray-900'
                                        }`}>
                                            {sesion.cita?.paciente?.nombre || 'N/A'}
                                        </h3>
                                    </div>
                                    <span className={`px-3 py-1 rounded-full text-sm font-medium ${
                                        sesion.estado === 'completada'
                                            ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200'
                                            : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200'
                                    }`}>
                                        {sesion.estado.charAt(0).toUpperCase() + sesion.estado.slice(1)}
                                    </span>
                                </div>

                                {/* Detalles */}
                                <div className={`grid grid-cols-2 gap-4 text-sm p-3 rounded ${
                                    theme === 'dark' ? 'bg-gray-700' : 'bg-gray-50'
                                }`}>
                                    <div>
                                        <p className={`font-medium ${
                                            theme === 'dark' ? 'text-gray-400' : 'text-gray-600'
                                        }`}>
                                            Fisioterapeuta
                                        </p>
                                        <p className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                            {sesion.cita?.fisioterapeuta?.nombre || 'N/A'}
                                        </p>
                                    </div>
                                    <div>
                                        <p className={`font-medium ${
                                            theme === 'dark' ? 'text-gray-400' : 'text-gray-600'
                                        }`}>
                                            Fecha
                                        </p>
                                        <p className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                            {format(new Date(sesion.created_at), 'dd MMM yyyy', { locale: es })}
                                        </p>
                                    </div>
                                </div>

                                {/* Notas Clínicas */}
                                {sesion.notas_clinicas && (
                                    <div className={`p-3 rounded text-sm ${
                                        theme === 'dark'
                                            ? 'bg-gray-700 text-gray-300'
                                            : 'bg-gray-50 text-gray-700'
                                    }`}>
                                        <p className="font-medium mb-1">Notas Clínicas:</p>
                                        <p>{sesion.notas_clinicas}</p>
                                    </div>
                                )}

                                {/* Acciones */}
                                <div className="flex gap-2 flex-wrap pt-2">
                                    {sesion.estado === 'pendiente' && (
                                        <Button
                                            size="sm"
                                            className="flex items-center gap-2 bg-green-500 hover:bg-green-600"
                                            onClick={() => handleCompletarSesion(sesion.id)}
                                            disabled={isLoading_mutation}
                                        >
                                            Completar
                                        </Button>
                                    )}
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        onClick={() => handleEditSesion(sesion)}
                                        disabled={isLoading_mutation}
                                    >
                                        Editar
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        onClick={() => handleDeleteSesion(sesion.id)}
                                        className="text-red-500 hover:text-red-600"
                                        disabled={isLoading_mutation}
                                    >
                                        Eliminar
                                    </Button>
                                </div>
                            </div>
                        </Card>
                    ))}
                </div>
            )}

            {/* Modal Formulario */}
            {isFormOpen && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <Card className={`w-full max-w-2xl ${theme === 'dark' ? 'dark' : ''}`}>
                        <div className="p-6 space-y-4">
                            <h2 className={`text-2xl font-bold ${
                                theme === 'dark' ? 'text-white' : 'text-gray-900'
                            }`}>
                                {selectedSesion ? 'Editar' : 'Nueva'} Sesión
                            </h2>

                            <form onSubmit={handleSubmitForm} className="space-y-4">
                                <div>
                                    <label className={`block text-sm font-medium mb-1 ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                                    }`}>
                                        Observaciones
                                    </label>
                                    <textarea
                                        value={formData.observaciones}
                                        onChange={(e) => setFormData({ ...formData, observaciones: e.target.value })}
                                        rows={3}
                                        className={`w-full px-3 py-2 rounded border resize-none ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300 text-gray-900'
                                        }`}
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-medium mb-1 ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                                    }`}>
                                        Notas Clínicas
                                    </label>
                                    <textarea
                                        value={formData.notas_clinicas}
                                        onChange={(e) => setFormData({ ...formData, notas_clinicas: e.target.value })}
                                        rows={3}
                                        className={`w-full px-3 py-2 rounded border resize-none ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300 text-gray-900'
                                        }`}
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-medium mb-1 ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                                    }`}>
                                        Resultados
                                    </label>
                                    <textarea
                                        value={formData.resultados}
                                        onChange={(e) => setFormData({ ...formData, resultados: e.target.value })}
                                        rows={2}
                                        className={`w-full px-3 py-2 rounded border resize-none ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300 text-gray-900'
                                        }`}
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-medium mb-1 ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                                    }`}>
                                        Próximas Recomendaciones
                                    </label>
                                    <textarea
                                        value={formData.proximas_recomendaciones}
                                        onChange={(e) => setFormData({ ...formData, proximas_recomendaciones: e.target.value })}
                                        rows={2}
                                        className={`w-full px-3 py-2 rounded border resize-none ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300 text-gray-900'
                                        }`}
                                    />
                                </div>

                                <div className="flex gap-3 justify-end pt-4">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => setIsFormOpen(false)}
                                        disabled={isLoading_mutation}
                                    >
                                        Cancelar
                                    </Button>
                                    <Button
                                        type="submit"
                                        disabled={isLoading_mutation}
                                        className="flex items-center gap-2"
                                    >
                                        {isLoading_mutation && <Loader2 className="w-4 h-4 animate-spin" />}
                                        {selectedSesion ? 'Actualizar' : 'Crear'}
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </Card>
                </div>
            )}
        </div>
    );
}
