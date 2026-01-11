import React, { useState } from 'react';
import { Calendar, Clock, User, MapPin, AlertCircle, Check, X } from 'lucide-react';
import { useGetCitas, useCambiarEstadoCita } from '../../hooks/useAgenda';
import { useTheme } from '../../components/theme-provider';
import { formatDate, format } from 'date-fns';
import { es } from 'date-fns/locale';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import { Input } from '../../components/ui/Input';
import { Loader2 } from 'lucide-react';

export default function CitasListView() {
    const { theme } = useTheme();
    const [filters, setFilters] = useState({ estado: '', fecha_desde: '', fecha_hasta: '' });
    const [expandedId, setExpandedId] = useState(null);

    const { data, isLoading, error } = useGetCitas(filters);
    const { mutate: cambiarEstado, isPending: isChangingState } = useCambiarEstadoCita();

    const citas = data?.data || [];

    const handleFilterChange = (field, value) => {
        setFilters(prev => ({ ...prev, [field]: value }));
    };

    const handleEstadoChange = (citaId, nuevoEstado) => {
        cambiarEstado(
            { id: citaId, estado: nuevoEstado },
            {
                onSuccess: () => {
                    // El query se invalida automáticamente
                },
            }
        );
    };

    const getEstadoBadgeColor = (estado) => {
        const colors = {
            'pendiente': 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            'confirmada': 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
            'cancelada': 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            'completada': 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
        };
        return colors[estado] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center p-12">
                <Loader2 className="w-8 h-8 animate-spin text-blue-500" />
                <span className="ml-2 text-gray-600 dark:text-gray-400">Cargando citas...</span>
            </div>
        );
    }

    if (error) {
        return (
            <div className={`p-4 rounded-lg flex items-center gap-3 ${
                theme === 'dark' ? 'bg-red-900 text-red-200' : 'bg-red-50 text-red-800'
            }`}>
                <AlertCircle className="w-5 h-5" />
                <span>Error al cargar citas: {error.message}</span>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {/* Filtros */}
            <Card className={theme === 'dark' ? 'dark' : ''}>
                <div className="p-4 space-y-4">
                    <h3 className={`font-semibold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                        Filtros
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label className={`block text-sm font-medium mb-1 ${
                                theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                            }`}>
                                Estado
                            </label>
                            <select
                                value={filters.estado}
                                onChange={(e) => handleFilterChange('estado', e.target.value)}
                                className={`w-full px-3 py-2 rounded border ${
                                    theme === 'dark'
                                        ? 'bg-gray-700 border-gray-600 text-white'
                                        : 'bg-white border-gray-300 text-gray-900'
                                }`}
                            >
                                <option value="">Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                                <option value="completada">Completada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>

                        <div>
                            <label className={`block text-sm font-medium mb-1 ${
                                theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                            }`}>
                                Desde
                            </label>
                            <Input
                                type="date"
                                value={filters.fecha_desde}
                                onChange={(e) => handleFilterChange('fecha_desde', e.target.value)}
                            />
                        </div>

                        <div>
                            <label className={`block text-sm font-medium mb-1 ${
                                theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
                            }`}>
                                Hasta
                            </label>
                            <Input
                                type="date"
                                value={filters.fecha_hasta}
                                onChange={(e) => handleFilterChange('fecha_hasta', e.target.value)}
                            />
                        </div>
                    </div>
                </div>
            </Card>

            {/* Lista de Citas */}
            <div className="space-y-4">
                {citas.length === 0 ? (
                    <Card className={theme === 'dark' ? 'dark' : ''}>
                        <div className={`p-8 text-center ${
                            theme === 'dark' ? 'text-gray-400' : 'text-gray-500'
                        }`}>
                            <Calendar className="w-12 h-12 mx-auto mb-3 opacity-50" />
                            <p>No hay citas con los filtros aplicados</p>
                        </div>
                    </Card>
                ) : (
                    citas.map((cita) => (
                        <Card key={cita.id} className={theme === 'dark' ? 'dark' : ''}>
                            <div className="p-4">
                                {/* Encabezado */}
                                <div className="flex items-start justify-between mb-4">
                                    <div>
                                        <h4 className={`font-semibold text-lg ${
                                            theme === 'dark' ? 'text-white' : 'text-gray-900'
                                        }`}>
                                            {cita.paciente?.nombre || 'Paciente'}
                                        </h4>
                                        <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                                            {cita.tipo_servicio?.nombre || 'Servicio'}
                                        </p>
                                    </div>
                                    <span className={`px-3 py-1 rounded-full text-sm font-medium ${getEstadoBadgeColor(cita.estado)}`}>
                                        {cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1)}
                                    </span>
                                </div>

                                {/* Detalles */}
                                <div className="grid grid-cols-2 gap-4 mb-4 text-sm">
                                    <div className="flex items-center gap-2">
                                        <Clock className="w-4 h-4 text-blue-500" />
                                        <span className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                            {format(new Date(cita.fecha_hora), 'HH:mm', { locale: es })}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Calendar className="w-4 h-4 text-blue-500" />
                                        <span className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                            {format(new Date(cita.fecha_hora), 'dd MMM yyyy', { locale: es })}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <User className="w-4 h-4 text-green-500" />
                                        <span className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                            {cita.fisioterapeuta?.nombre || 'Fisioterapeuta'}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <MapPin className="w-4 h-4 text-purple-500" />
                                        <span className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                            {cita.sala?.nombre || 'Sala'}
                                        </span>
                                    </div>
                                </div>

                                {/* Notas */}
                                {cita.notas && (
                                    <div className={`mb-4 p-3 rounded text-sm ${
                                        theme === 'dark'
                                            ? 'bg-gray-700 text-gray-300'
                                            : 'bg-gray-50 text-gray-700'
                                    }`}>
                                        <p className="font-medium mb-1">Notas:</p>
                                        <p>{cita.notas}</p>
                                    </div>
                                )}

                                {/* Acciones */}
                                <div className="flex gap-2 flex-wrap">
                                    {cita.estado === 'pendiente' && (
                                        <>
                                            <Button
                                                size="sm"
                                                className="flex items-center gap-2 bg-green-500 hover:bg-green-600"
                                                onClick={() => handleEstadoChange(cita.id, 'confirmada')}
                                                disabled={isChangingState}
                                            >
                                                <Check className="w-4 h-4" />
                                                Confirmar
                                            </Button>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                className="flex items-center gap-2"
                                                onClick={() => handleEstadoChange(cita.id, 'cancelada')}
                                                disabled={isChangingState}
                                            >
                                                <X className="w-4 h-4" />
                                                Cancelar
                                            </Button>
                                        </>
                                    )}
                                    {cita.estado === 'confirmada' && (
                                        <Button
                                            size="sm"
                                            className="flex items-center gap-2 bg-blue-500 hover:bg-blue-600"
                                            onClick={() => handleEstadoChange(cita.id, 'completada')}
                                            disabled={isChangingState}
                                        >
                                            <Check className="w-4 h-4" />
                                            Completar
                                        </Button>
                                    )}
                                </div>
                            </div>
                        </Card>
                    ))
                )}
            </div>
        </div>
    );
}
