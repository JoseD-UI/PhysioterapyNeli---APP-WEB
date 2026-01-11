import React, { useState, useEffect } from 'react';
import { X, AlertCircle, Loader2 } from 'lucide-react';
import { useCreateCita, useUpdateCita } from '../../hooks/useAgenda';
import { useGetPersonasPorTipo, useGetSalas } from '../../hooks/usePrincipal';
import { useGetTiposServicio } from '../../hooks/useClinico';
import { useTheme } from '../../components/theme-provider';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { Card } from '../../components/ui/Card';
import { format } from 'date-fns';

export default function CitaFormModal({ isOpen, onClose, onSuccess, cita = null, defaultDate = null }) {
    const { theme } = useTheme();
    const [formData, setFormData] = useState({
        paciente_id: '',
        fisioterapeuta_id: '',
        fecha_hora: '',
        sala_id: '',
        tipo_servicio_id: '',
        notas: '',
    });
    const [errors, setErrors] = useState({});

    // Queries
    const { data: pacientes } = useGetPersonasPorTipo('paciente');
    const { data: fisioterapeutas } = useGetPersonasPorTipo('fisioterapeuta');
    const { data: salas } = useGetSalas();
    const { data: tiposServicio } = useGetTiposServicio();

    // Mutations
    const { mutate: createCita, isPending: isCreating } = useCreateCita();
    const { mutate: updateCita, isPending: isUpdating } = useUpdateCita();

    const isLoading = isCreating || isUpdating;

    // Inicializar formulario
    useEffect(() => {
        if (cita) {
            setFormData({
                paciente_id: cita.paciente_id,
                fisioterapeuta_id: cita.fisioterapeuta_id,
                fecha_hora: format(new Date(cita.fecha_hora), 'yyyy-MM-ddHH:mm'),
                sala_id: cita.sala_id,
                tipo_servicio_id: cita.tipo_servicio_id,
                notas: cita.notas || '',
            });
        } else if (defaultDate) {
            setFormData(prev => ({
                ...prev,
                fecha_hora: format(new Date(defaultDate), 'yyyy-MM-ddHH:mm'),
            }));
        }
    }, [cita, defaultDate]);

    const handleChange = (field, value) => {
        setFormData(prev => ({ ...prev, [field]: value }));
        if (errors[field]) {
            setErrors(prev => ({ ...prev, [field]: '' }));
        }
    };

    const validateForm = () => {
        const newErrors = {};

        if (!formData.paciente_id) newErrors.paciente_id = 'Selecciona un paciente';
        if (!formData.fisioterapeuta_id) newErrors.fisioterapeuta_id = 'Selecciona un fisioterapeuta';
        if (!formData.fecha_hora) newErrors.fecha_hora = 'Ingresa fecha y hora';
        if (!formData.sala_id) newErrors.sala_id = 'Selecciona una sala';
        if (!formData.tipo_servicio_id) newErrors.tipo_servicio_id = 'Selecciona un tipo de servicio';

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        if (!validateForm()) return;

        const submitData = {
            ...formData,
            fecha_hora: new Date(formData.fecha_hora).toISOString(),
        };

        if (cita) {
            updateCita(
                { id: cita.id, data: submitData },
                {
                    onSuccess: () => {
                        setFormData({
                            paciente_id: '',
                            fisioterapeuta_id: '',
                            fecha_hora: '',
                            sala_id: '',
                            tipo_servicio_id: '',
                            notas: '',
                        });
                        setErrors({});
                        onSuccess?.();
                        onClose();
                    },
                }
            );
        } else {
            createCita(submitData, {
                onSuccess: () => {
                    setFormData({
                        paciente_id: '',
                        fisioterapeuta_id: '',
                        fecha_hora: '',
                        sala_id: '',
                        tipo_servicio_id: '',
                        notas: '',
                    });
                    setErrors({});
                    onSuccess?.();
                    onClose();
                },
            });
        }
    };

    if (!isOpen) return null;

    const FormField = ({ label, field, type = 'text', required = true, children }) => (
        <div>
            <label className={`block text-sm font-medium mb-1 ${
                theme === 'dark' ? 'text-gray-300' : 'text-gray-700'
            }`}>
                {label}
                {required && <span className="text-red-500 ml-1">*</span>}
            </label>
            {children}
            {errors[field] && (
                <p className="text-sm text-red-500 mt-1 flex items-center gap-1">
                    <AlertCircle className="w-4 h-4" />
                    {errors[field]}
                </p>
            )}
        </div>
    );

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <Card className={`w-full max-w-2xl ${theme === 'dark' ? 'dark' : ''}`}>
                <div className="p-6 space-y-6">
                    {/* Header */}
                    <div className="flex items-center justify-between">
                        <h2 className={`text-2xl font-bold ${
                            theme === 'dark' ? 'text-white' : 'text-gray-900'
                        }`}>
                            {cita ? 'Editar Cita' : 'Nueva Cita'}
                        </h2>
                        <button
                            onClick={onClose}
                            className={`p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition ${
                                theme === 'dark' ? 'text-gray-300' : 'text-gray-600'
                            }`}
                        >
                            <X className="w-6 h-6" />
                        </button>
                    </div>

                    {/* Formulario */}
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {/* Paciente */}
                            <FormField label="Paciente" field="paciente_id">
                                <select
                                    value={formData.paciente_id}
                                    onChange={(e) => handleChange('paciente_id', e.target.value)}
                                    className={`w-full px-3 py-2 rounded border ${
                                        theme === 'dark'
                                            ? 'bg-gray-700 border-gray-600 text-white'
                                            : 'bg-white border-gray-300 text-gray-900'
                                    }`}
                                >
                                    <option value="">Selecciona un paciente</option>
                                    {pacientes?.data?.map(p => (
                                        <option key={p.id} value={p.id}>
                                            {p.nombre}
                                        </option>
                                    ))}
                                </select>
                            </FormField>

                            {/* Fisioterapeuta */}
                            <FormField label="Fisioterapeuta" field="fisioterapeuta_id">
                                <select
                                    value={formData.fisioterapeuta_id}
                                    onChange={(e) => handleChange('fisioterapeuta_id', e.target.value)}
                                    className={`w-full px-3 py-2 rounded border ${
                                        theme === 'dark'
                                            ? 'bg-gray-700 border-gray-600 text-white'
                                            : 'bg-white border-gray-300 text-gray-900'
                                    }`}
                                >
                                    <option value="">Selecciona un fisioterapeuta</option>
                                    {fisioterapeutas?.data?.map(f => (
                                        <option key={f.id} value={f.id}>
                                            {f.nombre}
                                        </option>
                                    ))}
                                </select>
                            </FormField>

                            {/* Fecha y Hora */}
                            <FormField label="Fecha y Hora" field="fecha_hora" type="datetime-local">
                                <Input
                                    type="datetime-local"
                                    value={formData.fecha_hora}
                                    onChange={(e) => handleChange('fecha_hora', e.target.value)}
                                    className="w-full"
                                />
                            </FormField>

                            {/* Sala */}
                            <FormField label="Sala" field="sala_id">
                                <select
                                    value={formData.sala_id}
                                    onChange={(e) => handleChange('sala_id', e.target.value)}
                                    className={`w-full px-3 py-2 rounded border ${
                                        theme === 'dark'
                                            ? 'bg-gray-700 border-gray-600 text-white'
                                            : 'bg-white border-gray-300 text-gray-900'
                                    }`}
                                >
                                    <option value="">Selecciona una sala</option>
                                    {salas?.data?.map(s => (
                                        <option key={s.id} value={s.id}>
                                            {s.nombre}
                                        </option>
                                    ))}
                                </select>
                            </FormField>

                            {/* Tipo de Servicio */}
                            <FormField label="Tipo de Servicio" field="tipo_servicio_id" required={true}>
                                <select
                                    value={formData.tipo_servicio_id}
                                    onChange={(e) => handleChange('tipo_servicio_id', e.target.value)}
                                    className={`w-full px-3 py-2 rounded border ${
                                        theme === 'dark'
                                            ? 'bg-gray-700 border-gray-600 text-white'
                                            : 'bg-white border-gray-300 text-gray-900'
                                    }`}
                                >
                                    <option value="">Selecciona un servicio</option>
                                    {tiposServicio?.data?.map(ts => (
                                        <option key={ts.id} value={ts.id}>
                                            {ts.nombre} (${ts.precio})
                                        </option>
                                    ))}
                                </select>
                            </FormField>
                        </div>

                        {/* Notas */}
                        <FormField label="Notas" field="notas" required={false}>
                            <textarea
                                value={formData.notas}
                                onChange={(e) => handleChange('notas', e.target.value)}
                                placeholder="Notas adicionales de la cita..."
                                rows={3}
                                className={`w-full px-3 py-2 rounded border resize-none ${
                                    theme === 'dark'
                                        ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500'
                                        : 'bg-white border-gray-300 text-gray-900 placeholder-gray-400'
                                }`}
                            />
                        </FormField>

                        {/* Botones */}
                        <div className="flex gap-3 justify-end pt-4">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={onClose}
                                disabled={isLoading}
                            >
                                Cancelar
                            </Button>
                            <Button
                                type="submit"
                                disabled={isLoading}
                                className="flex items-center gap-2"
                            >
                                {isLoading ? (
                                    <>
                                        <Loader2 className="w-4 h-4 animate-spin" />
                                        Guardando...
                                    </>
                                ) : (
                                    cita ? 'Actualizar' : 'Crear'
                                )}
                            </Button>
                        </div>
                    </form>
                </div>
            </Card>
        </div>
    );
}
