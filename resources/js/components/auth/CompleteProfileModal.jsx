import React, { useState, useEffect } from 'react';
import { useAuth } from '../../hooks/useAuth';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { useAuthStore } from '../../stores/authStore';
import { useNavigate } from 'react-router-dom';
import api from '../../lib/axios';
import { AlertCircle } from 'lucide-react';

export default function CompleteProfileModal() {
    const { user, checkAuth } = useAuth();
    const navigate = useNavigate();
    
    // Si no hay usuario (guest) o perfil está completo, no mostramos nada
    if (!user || user.profile_complete) return null;

    const [isOpen, setIsOpen] = useState(true);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    const [tipoPersona, setTipoPersona] = useState('PACIENTE-NATURAL'); // Default
    
    // Estado del formulario
    const [formData, setFormData] = useState({
        documento_tipo: 'DNI',
        documento_numero: '',
        telefono: '',
        direccion: '',
        nombres: '', // Para Razón Social en Jurídica
        apellidos: '', // Para actualización si falta
        fecha_nacimiento: ''
    });

    // Pofill data si existe (e.g. Google trajo nombres)
    useEffect(() => {
        if (user?.usuario_principal?.persona) {
            const p = user.usuario_principal.persona;
            setFormData(prev => ({
                ...prev,
                nombres: p.nombres || '',
                apellidos: p.apellidos || '',
                telefono: p.telefono || '',
                direccion: p.direccion || '',
            }));
            
            // Si ya tenía DNI/RUC inválido, lo seteamos
             if (p.dni) {
                 setTipoPersona('PACIENTE-NATURAL');
                 setFormData(prev => ({...prev, documento_tipo: 'DNI', documento_numero: p.dni}));
             } else if (p.ruc) {
                 setTipoPersona('PACIENTE-JURIDICA');
                 setFormData(prev => ({...prev, documento_tipo: 'RUC', documento_numero: p.ruc}));
             }
        }
    }, [user]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setIsLoading(true);

        try {
            const payload = {
                ...formData,
                tipo_persona: tipoPersona
            };

            // Ajustar payloads según tipo
            if (tipoPersona === 'PACIENTE-JURIDICA') {
                payload.documento_tipo = 'RUC';
            }

            await api.post('/auth/complete-profile', payload);
            
            // Recargar usuario para actualizar flags
            await checkAuth();
            
            // Si todo ok, el componente dejara de renderizarse
        } catch (err) {
            console.error(err);
            if (err.response?.data?.errors) {
                 setError(Object.values(err.response.data.errors).flat().join(' '));
            } else {
                 setError('Error al actualizar perfil. Verifique sus datos.');
            }
        } finally {
            setIsLoading(false);
        }
    };

    // Bloqueo estricto: Renderiza un overlay fijo
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div className="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden">
                <div className="p-6 bg-red-50 border-b border-red-100">
                    <h2 className="text-xl font-bold text-red-700 flex items-center gap-2">
                        <AlertCircle className="w-6 h-6" />
                        Perfil Incompleto
                    </h2>
                    <p className="text-sm text-red-600 mt-1">
                        Para continuar, necesitamos completar su información de registro obligatoria.
                    </p>
                </div>
                
                <div className="p-6">
                    {error && (
                        <div className="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded border border-red-200">
                            {error}
                        </div>
                    )}

                    <form onSubmit={handleSubmit} className="space-y-4">
                        
                        {/* Selector Tipo */}
                        <div className="flex gap-4 p-1 bg-gray-100 rounded-lg">
                            <button
                                type="button"
                                onClick={() => {
                                    setTipoPersona('PACIENTE-NATURAL');
                                    setFormData(prev => ({ ...prev, documento_tipo: 'DNI' }));
                                }}
                                className={`flex-1 py-2 text-sm font-medium rounded-md transition-all ${
                                    tipoPersona === 'PACIENTE-NATURAL' 
                                        ? 'bg-white text-blue-600 shadow-sm ring-1 ring-gray-200' 
                                        : 'text-gray-500 hover:text-gray-700'
                                }`}
                            >
                                Persona Natural
                            </button>
                            <button
                                type="button"
                                onClick={() => {
                                    setTipoPersona('PACIENTE-JURIDICA');
                                    setFormData(prev => ({ ...prev, documento_tipo: 'RUC' }));
                                }}
                                className={`flex-1 py-2 text-sm font-medium rounded-md transition-all ${
                                    tipoPersona === 'PACIENTE-JURIDICA'
                                        ? 'bg-white text-blue-600 shadow-sm ring-1 ring-gray-200'
                                        : 'text-gray-500 hover:text-gray-700'
                                }`}
                            >
                                Persona Jurídica
                            </button>
                        </div>

                        <div className="grid gap-4">
                            {tipoPersona === 'PACIENTE-NATURAL' ? (
                                <>
                                    <div className="grid grid-cols-3 gap-2">
                                        <select
                                            name="documento_tipo"
                                            value={formData.documento_tipo}
                                            onChange={handleChange}
                                            className="col-span-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="DNI">DNI (8)</option>
                                            <option value="CARNET_EXT">C.E. (9)</option>
                                        </select>
                                        <Input
                                            name="documento_numero"
                                            placeholder="Número de Documento"
                                            value={formData.documento_numero}
                                            onChange={handleChange}
                                            maxLength={formData.documento_tipo === 'DNI' ? 8 : 9}
                                            className="col-span-2"
                                            required
                                        />
                                    </div>
                                    <div className="grid grid-cols-2 gap-4">
                                        <Input
                                            label="Nombres"
                                            name="nombres"
                                            value={formData.nombres}
                                            onChange={handleChange}
                                            required
                                        />
                                        <Input
                                            label="Apellidos"
                                            name="apellidos"
                                            value={formData.apellidos}
                                            onChange={handleChange}
                                            required
                                        />
                                    </div>
                                    <Input
                                        type="date"
                                        label="Fecha de Nacimiento"
                                        name="fecha_nacimiento"
                                        value={formData.fecha_nacimiento}
                                        onChange={handleChange}
                                        required
                                    />
                                </>
                            ) : (
                                <>
                                     <Input
                                        label="RUC (11 dígitos)"
                                        name="documento_numero"
                                        placeholder="20123456789"
                                        value={formData.documento_numero}
                                        onChange={handleChange}
                                        maxLength={11}
                                        required
                                    />
                                    <Input
                                        label="Razón Social"
                                        name="nombres"
                                        placeholder="Nombre de la Empresa"
                                        value={formData.nombres}
                                        onChange={handleChange}
                                        required
                                    />
                                </>
                            )}

                            <Input
                                label="Teléfono / Celular"
                                name="telefono"
                                placeholder="999888777"
                                value={formData.telefono}
                                onChange={handleChange}
                                required
                            />
                            
                            <Input
                                label="Dirección Completa"
                                name="direccion"
                                placeholder="Av. Principal 123, Distrito"
                                value={formData.direccion}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <Button type="submit" className="w-full mt-6" isLoading={isLoading}>
                            Guardar y Continuar
                        </Button>
                        
                        <div className="text-center mt-4 border-t pt-4">
                             <p className="text-xs text-gray-500 mb-2">
                                 ¿Desea hacer esto más tarde?
                             </p>
                             <button 
                                type="button" 
                                onClick={() => {
                                    useAuthStore.getState().logout();
                                }} 
                                className="text-sm font-medium text-gray-600 hover:text-red-600 transition-colors"
                             >
                                 Cerrar Sesión y Volver al Inicio
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
