import React, { useState } from 'react';
import { FileText, Plus, AlertCircle, Loader2 } from 'lucide-react';
import { useGetHistorias, useCreateHistoria, useUpdateHistoria, useDeleteHistoria } from '../../hooks/useClinico';
import { useTheme } from '../../components/theme-provider';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import { Input } from '../../components/ui/Input';
import { format } from 'date-fns';

export default function HistoriaClinicaView() {
    const { theme } = useTheme();
    const [filters, setFilters] = useState({ paciente_id: '' });
    const [isFormOpen, setIsFormOpen] = useState(false);
    const [selectedHistoria, setSelectedHistoria] = useState(null);
    const [formData, setFormData] = useState({
        paciente_id: '',
        diagnostico: '',
        anamnesis: '',
        observaciones: '',
    });

    const { data, isLoading, error } = useGetHistorias(filters);
    const { mutate: createHistoria, isPending: isCreating } = useCreateHistoria();
    const { mutate: updateHistoria, isPending: isUpdating } = useUpdateHistoria();
    const { mutate: deleteHistoria, isPending: isDeleting } = useDeleteHistoria();

    const historias = data?.data || [];
    const isLoading_mutation = isCreating || isUpdating || isDeleting;

    const handleNewHistoria = () => {
        setSelectedHistoria(null);
        setFormData({
            paciente_id: '',
            diagnostico: '',
            anamnesis: '',
            observaciones: '',
        });
        setIsFormOpen(true);
    };

    const handleEditHistoria = (historia) => {
        setSelectedHistoria(historia);
        setFormData({
            paciente_id: historia.paciente_id,
            diagnostico: historia.diagnostico,
            anamnesis: historia.anamnesis,
            observaciones: historia.observaciones,
        });
        setIsFormOpen(true);
    };

    const handleSubmitForm = (e) => {
        e.preventDefault();

        if (selectedHistoria) {
            updateHistoria(
                { id: selectedHistoria.id, data: formData },
                { onSuccess: () => setIsFormOpen(false) }
            );
        } else {
            createHistoria(formData, { onSuccess: () => setIsFormOpen(false) });
        }
    };

    const handleDeleteHistoria = (id) => {
        if (window.confirm('¿Estás seguro de que deseas eliminar esta historia clínica?')) {
            deleteHistoria(id);
        }
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center p-12">
                <Loader2 className="w-8 h-8 animate-spin text-blue-500" />
                <span className="ml-2 text-gray-600 dark:text-gray-400">Cargando historias clínicas...</span>
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
                    <FileText className="w-6 h-6 text-blue-500" />
                    Historias Clínicas
                </h2>
                <Button
                    onClick={handleNewHistoria}
                    className="flex items-center gap-2 bg-blue-500 hover:bg-blue-600"
                >
                    <Plus className="w-4 h-4" />
                    Nueva Historia
                </Button>
            </div>

            {/* Lista */}
            {historias.length === 0 ? (
                <Card className={theme === 'dark' ? 'dark' : ''}>
                    <div className={`p-8 text-center ${
                        theme === 'dark' ? 'text-gray-400' : 'text-gray-500'
                    }`}>
                        <FileText className="w-12 h-12 mx-auto mb-3 opacity-50" />
                        <p>No hay historias clínicas registradas</p>
                    </div>
                </Card>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {historias.map((historia) => (
                        <Card key={historia.id} className={theme === 'dark' ? 'dark' : ''}>
                            <div className="p-4 space-y-3">
                                <div>
                                    <p className={`text-sm font-medium ${
                                        theme === 'dark' ? 'text-gray-400' : 'text-gray-600'
                                    }`}>
                                        Paciente
                                    </p>
                                    <h3 className={`font-bold text-lg ${
                                        theme === 'dark' ? 'text-white' : 'text-gray-900'
                                    }`}>
                                        {historia.paciente?.nombre || 'N/A'}
                                    </h3>
                                </div>

                                <div>
                                    <p className={`text-sm font-medium mb-1 ${
                                        theme === 'dark' ? 'text-gray-400' : 'text-gray-600'
                                    }`}>
                                        Diagnóstico
                                    </p>
                                    <p className={`text-sm ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                                    }`}>
                                        {historia.diagnostico}
                                    </p>
                                </div>

                                <div>
                                    <p className={`text-xs ${
                                        theme === 'dark' ? 'text-gray-500' : 'text-gray-500'
                                    }`}>
                                        Creado: {format(new Date(historia.created_at), 'dd/MM/yyyy')}
                                    </p>
                                </div>

                                <div className="flex gap-2 pt-2">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        onClick={() => handleEditHistoria(historia)}
                                        className="flex-1"
                                        disabled={isLoading_mutation}
                                    >
                                        Editar
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        onClick={() => handleDeleteHistoria(historia.id)}
                                        className="flex-1 text-red-500 hover:text-red-600"
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
                                {selectedHistoria ? 'Editar' : 'Nueva'} Historia Clínica
                            </h2>

                            <form onSubmit={handleSubmitForm} className="space-y-4">
                                <div>
                                    <label className={`block text-sm font-medium mb-1 ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                                    }`}>
                                        Diagnóstico
                                    </label>
                                    <textarea
                                        value={formData.diagnostico}
                                        onChange={(e) => setFormData({ ...formData, diagnostico: e.target.value })}
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
                                        Anamnesis
                                    </label>
                                    <textarea
                                        value={formData.anamnesis}
                                        onChange={(e) => setFormData({ ...formData, anamnesis: e.target.value })}
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
                                        {selectedHistoria ? 'Actualizar' : 'Crear'}
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
