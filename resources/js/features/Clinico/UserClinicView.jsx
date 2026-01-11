import React, { useState, useMemo } from 'react';
import { FileText, Activity, Pill, Calendar, Clock, User, TrendingUp, AlertCircle } from 'lucide-react';
import { useGetHistorias } from '../../hooks/useClinico';
import { useTheme } from '../../components/theme-provider';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import { Card } from '../../components/ui/Card';

export default function UserClinicView() {
    const { theme } = useTheme();
    const [expandedHistory, setExpandedHistory] = useState(null);
    
    // Obtener historia clínica del usuario actual (usar el ID del usuario logueado)
    const { data: historiesData, isLoading, error } = useGetHistorias({ 
        persona_id: localStorage.getItem('persona_id') 
    });
    
    const histories = historiesData?.data || [];
    
    // Mock data para sesiones (en producción vendría del backend)
    const mockSessions = [
        {
            sesion_id: '1',
            fecha_atencion: new Date(2026, 0, 8),
            servicio: 'Masaje Terapéutico',
            duracion_minutos: 60,
            fisioterapeuta: 'Dr. Carlos Mendez',
            notas: 'Sesión de relajación muscular enfocada en espalda baja',
            ejercicios_realizados: 'Estiramientos dorsales, movilidad articular',
            resultado: 'Mejoría del 40%'
        },
        {
            sesion_id: '2',
            fecha_atencion: new Date(2026, 0, 5),
            servicio: 'Terapia Física',
            duracion_minutos: 45,
            fisioterapeuta: 'Dra. María López',
            notas: 'Fortalecimiento de core',
            ejercicios_realizados: 'Planchas, abdominales isométricos',
            resultado: 'Excelente progreso'
        },
        {
            sesion_id: '3',
            fecha_atencion: new Date(2026, 0, 1),
            servicio: 'Evaluación Inicial',
            duracion_minutos: 90,
            fisioterapeuta: 'Dr. Carlos Mendez',
            notas: 'Evaluación completa y creación de plan de tratamiento',
            ejercicios_realizados: 'N/A',
            resultado: 'Plan personalizado creado'
        }
    ];

    // Calcular estadísticas
    const stats = useMemo(() => {
        return {
            totalSessions: mockSessions.length,
            totalMinutes: mockSessions.reduce((acc, s) => acc + (s.duracion_minutos || 0), 0),
            lastSession: mockSessions[0]?.fecha_atencion,
            improvement: '65%' // Promedio de mejora
        };
    }, []);

    if (isLoading) {
        return (
            <div className={`min-h-screen ${theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50'} p-6`}>
                <div className="text-center">
                    <div className={`inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent ${theme === 'dark' ? 'text-blue-400' : 'text-blue-600'}`}></div>
                    <p className={`mt-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-600'}`}>
                        Cargando información clínica...
                    </p>
                </div>
            </div>
        );
    }

    return (
        <div className={`min-h-screen ${theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50'} p-6`}>
            <div className="max-w-7xl mx-auto">
                {/* Header */}
                <div className="mb-8">
                    <h1 className={`text-3xl font-bold mb-2 ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                        📋 Mi Información Clínica
                    </h1>
                    <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                        Visualiza tu historial médico, sesiones y progreso de tratamiento
                    </p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-4`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                    Sesiones Completadas
                                </p>
                                <p className={`text-2xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    {stats.totalSessions}
                                </p>
                            </div>
                            <Activity className={theme === 'dark' ? 'text-blue-400' : 'text-blue-600'} size={32} />
                        </div>
                    </Card>

                    <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-4`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                    Minutos de Terapia
                                </p>
                                <p className={`text-2xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    {stats.totalMinutes}
                                </p>
                            </div>
                            <Clock className={theme === 'dark' ? 'text-green-400' : 'text-green-600'} size={32} />
                        </div>
                    </Card>

                    <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-4`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                    Última Sesión
                                </p>
                                <p className={`text-lg font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    {stats.lastSession ? format(stats.lastSession, 'dd/MM', { locale: es }) : 'N/A'}
                                </p>
                            </div>
                            <Calendar className={theme === 'dark' ? 'text-purple-400' : 'text-purple-600'} size={32} />
                        </div>
                    </Card>

                    <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-4`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                    Progreso
                                </p>
                                <p className={`text-2xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    {stats.improvement}
                                </p>
                            </div>
                            <TrendingUp className={theme === 'dark' ? 'text-orange-400' : 'text-orange-600'} size={32} />
                        </div>
                    </Card>
                </div>

                {/* Error Message */}
                {error && (
                    <Card className={`${theme === 'dark' ? 'bg-red-900/20 border-red-700' : 'bg-red-50 border-red-200'} p-4 mb-8 border`}>
                        <div className="flex items-center gap-3">
                            <AlertCircle className={theme === 'dark' ? 'text-red-400' : 'text-red-600'} />
                            <p className={theme === 'dark' ? 'text-red-300' : 'text-red-800'}>
                                Error al cargar la información clínica. Por favor, intenta nuevamente.
                            </p>
                        </div>
                    </Card>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Historia Clínica */}
                    <div className="lg:col-span-2">
                        <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-6 mb-8`}>
                            <div className="flex items-center gap-3 mb-6">
                                <FileText className={theme === 'dark' ? 'text-blue-400' : 'text-blue-600'} size={24} />
                                <h2 className={`text-xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    Historia Clínica
                                </h2>
                            </div>

                            {histories.length > 0 ? (
                                histories.map((history, index) => (
                                    <div key={history.historia_id || index}>
                                        <div
                                            onClick={() => setExpandedHistory(expandedHistory === (history.historia_id || index) ? null : (history.historia_id || index))}
                                            className={`p-4 mb-4 rounded-lg cursor-pointer transition ${
                                                theme === 'dark'
                                                    ? 'bg-gray-700 hover:bg-gray-600'
                                                    : 'bg-gray-100 hover:bg-gray-200'
                                            }`}
                                        >
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className={`w-3 h-3 rounded-full ${theme === 'dark' ? 'bg-blue-400' : 'bg-blue-600'}`} />
                                                    <div>
                                                        <p className={`font-semibold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                                            {history.diagnostico_inicial || 'Sin diagnóstico'}
                                                        </p>
                                                        <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                            Actualizado: {format(new Date(history.actualizado_en), 'dd/MM/yyyy', { locale: es })}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div className={`transition-transform ${expandedHistory === (history.historia_id || index) ? 'rotate-180' : ''}`}>
                                                    →
                                                </div>
                                            </div>

                                            {expandedHistory === (history.historia_id || index) && (
                                                <div className={`mt-4 pt-4 border-t ${theme === 'dark' ? 'border-gray-600' : 'border-gray-300'}`}>
                                                    {history.motivo_consulta && (
                                                        <div className="mb-3">
                                                            <p className={`font-semibold text-sm ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                                                Motivo de Consulta:
                                                            </p>
                                                            <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                                                                {history.motivo_consulta}
                                                            </p>
                                                        </div>
                                                    )}

                                                    {history.antecedentes && (
                                                        <div className="mb-3">
                                                            <p className={`font-semibold text-sm ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                                                Antecedentes:
                                                            </p>
                                                            <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                                                                {history.antecedentes}
                                                            </p>
                                                        </div>
                                                    )}

                                                    {history.alergias && (
                                                        <div className="mb-3">
                                                            <p className={`font-semibold text-sm ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                                                Alergias:
                                                            </p>
                                                            <p className={`${theme === 'dark' ? 'text-red-400' : 'text-red-600'} font-semibold`}>
                                                                {history.alergias}
                                                            </p>
                                                        </div>
                                                    )}

                                                    {history.recomendaciones && (
                                                        <div>
                                                            <p className={`font-semibold text-sm ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                                                Recomendaciones:
                                                            </p>
                                                            <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                                                                {history.recomendaciones}
                                                            </p>
                                                        </div>
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className={`text-center py-8 ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                    <FileText size={32} className="mx-auto mb-2 opacity-50" />
                                    <p>No hay historia clínica registrada aún</p>
                                </div>
                            )}
                        </Card>
                    </div>

                    {/* Servicios Consumidos */}
                    <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-6 h-fit`}>
                        <div className="flex items-center gap-3 mb-6">
                            <Pill className={theme === 'dark' ? 'text-green-400' : 'text-green-600'} size={24} />
                            <h2 className={`text-xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                Servicios
                            </h2>
                        </div>

                        <div className="space-y-3">
                            {[
                                { name: 'Masaje Terapéutico', count: 2, duration: 60 },
                                { name: 'Terapia Física', count: 1, duration: 45 },
                                { name: 'Evaluación Inicial', count: 1, duration: 90 }
                            ].map((service, idx) => (
                                <div
                                    key={idx}
                                    className={`p-3 rounded-lg ${theme === 'dark' ? 'bg-gray-700' : 'bg-gray-100'}`}
                                >
                                    <p className={`font-semibold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                        {service.name}
                                    </p>
                                    <div className={`text-sm mt-1 ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                        <p>Sesiones: {service.count}</p>
                                        <p>Duración: {service.count * service.duration} min</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </Card>
                </div>

                {/* Sesiones */}
                <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-6 mt-8`}>
                    <div className="flex items-center gap-3 mb-6">
                        <Activity className={theme === 'dark' ? 'text-purple-400' : 'text-purple-600'} size={24} />
                        <h2 className={`text-xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                            Mis Sesiones
                        </h2>
                    </div>

                    <div className="space-y-4">
                        {mockSessions.map((session, idx) => (
                            <div
                                key={session.sesion_id}
                                className={`p-4 rounded-lg border ${
                                    theme === 'dark'
                                        ? 'bg-gray-700 border-gray-600'
                                        : 'bg-gray-50 border-gray-200'
                                }`}
                            >
                                <div className="flex items-start justify-between mb-3">
                                    <div>
                                        <p className={`font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                            {session.servicio}
                                        </p>
                                        <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                            {format(session.fecha_atencion, 'EEEE dd MMMM yyyy - HH:mm', { locale: es })}
                                        </p>
                                    </div>
                                    <div className={`px-3 py-1 rounded-full text-sm font-semibold ${
                                        theme === 'dark'
                                            ? 'bg-green-900/30 text-green-300'
                                            : 'bg-green-100 text-green-800'
                                    }`}>
                                        ✓ Completada
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <p className={`text-xs ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                            Duración
                                        </p>
                                        <p className={`font-semibold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                            {session.duracion_minutos} min
                                        </p>
                                    </div>
                                    <div>
                                        <p className={`text-xs ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                            Terapeuta
                                        </p>
                                        <p className={`font-semibold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                            {session.fisioterapeuta}
                                        </p>
                                    </div>
                                </div>

                                {session.ejercicios_realizados && (
                                    <div className="mb-3">
                                        <p className={`text-sm font-semibold ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                            Ejercicios Realizados:
                                        </p>
                                        <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                            {session.ejercicios_realizados}
                                        </p>
                                    </div>
                                )}

                                {session.notas && (
                                    <div className="mb-3">
                                        <p className={`text-sm font-semibold ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                            Notas:
                                        </p>
                                        <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                            {session.notas}
                                        </p>
                                    </div>
                                )}

                                <div className={`p-3 rounded-lg ${
                                    theme === 'dark'
                                        ? 'bg-blue-900/20 text-blue-300'
                                        : 'bg-blue-50 text-blue-700'
                                }`}>
                                    <p className="text-sm font-semibold">📊 Resultado: {session.resultado}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </Card>
            </div>
        </div>
    );
}
