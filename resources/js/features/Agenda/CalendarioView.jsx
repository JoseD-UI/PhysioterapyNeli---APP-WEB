import React, { useState, useMemo } from 'react';
import { Calendar, MapPin, User, Clock, Plus, Edit2 } from 'lucide-react';
import { Calendar as BigCalendar, dateFnsLocalizer } from 'react-big-calendar';
import { format, parse, startOfWeek, getDay } from 'date-fns';
import { es } from 'date-fns/locale';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import { useGetCitas, useReprogramarCita } from '../../hooks/useAgenda';
import { useTheme } from '../../components/theme-provider';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import '../../../css/calendar.css'; // Custom calendar styles with dark mode

const locales = { es };
const localizer = dateFnsLocalizer({
    format,
    parse,
    startOfWeek,
    getDay,
    locales,
});

export default function CalendarioView({ onSelectCita, onNewCita }) {
    const { theme } = useTheme();
    const [view, setView] = useState('month');
    const [date, setDate] = useState(new Date());
    const [selectedCita, setSelectedCita] = useState(null);

    const { data, isLoading } = useGetCitas();
    const { mutate: reprogramarCita } = useReprogramarCita();

    // Transformar citas al formato de React Big Calendar
    const events = useMemo(() => {
        if (!data?.data) return [];

        return data.data.map(cita => ({
            id: cita.id,
            title: `${cita.paciente?.nombre || 'Paciente'} - ${cita.tipo_servicio?.nombre || 'Servicio'}`,
            start: new Date(cita.fecha_hora),
            end: new Date(new Date(cita.fecha_hora).getTime() + 60 * 60 * 1000), // +1 hora
            resource: cita,
            estado: cita.estado,
        }));
    }, [data]);

    const handleSelectEvent = (event) => {
        setSelectedCita(event.resource);
        if (onSelectCita) onSelectCita(event.resource);
    };

    const handleSelectSlot = (slotInfo) => {
        if (onNewCita) onNewCita(slotInfo.start);
    };

    const getEventStyleGetter = (event) => {
        let backgroundColor = '#3b82f6'; // blue por defecto

        switch (event.estado) {
            case 'confirmada':
                backgroundColor = '#10b981'; // green
                break;
            case 'completada':
                backgroundColor = '#6366f1'; // indigo
                break;
            case 'cancelada':
                backgroundColor = '#ef4444'; // red
                break;
            case 'pendiente':
                backgroundColor = '#f59e0b'; // amber
                break;
            default:
                backgroundColor = '#8b5cf6'; // purple
        }

        return {
            style: {
                backgroundColor,
                borderRadius: '4px',
                opacity: 0.8,
                color: 'white',
                border: '0px',
                display: 'block',
                cursor: 'pointer',
            }
        };
    };

    return (
        <div className={`space-y-6 ${theme === 'dark' ? 'dark' : ''}`}>
            {/* Header */}
            <div className="flex items-center justify-between mb-6">
                <h2 className={`text-2xl font-bold flex items-center gap-2 ${
                    theme === 'dark' ? 'text-white' : 'text-gray-900'
                }`}>
                    <Calendar className="w-6 h-6 text-blue-500" />
                    Calendario de Citas
                </h2>
                <Button
                    onClick={() => onNewCita && onNewCita(new Date())}
                    className="flex items-center gap-2 bg-blue-500 hover:bg-blue-600"
                >
                    <Plus className="w-4 h-4" />
                    Nueva Cita
                </Button>
            </div>

            {/* Calendario */}
            <Card className={theme === 'dark' ? 'dark' : ''}>
                <div className={`calendar-wrapper ${theme === 'dark' ? 'dark-calendar' : ''}`}>
                    <BigCalendar
                        localizer={localizer}
                        events={events}
                        startAccessor="start"
                        endAccessor="end"
                        style={{ height: 600 }}
                        view={view}
                        onView={setView}
                        date={date}
                        onNavigate={setDate}
                        onSelectEvent={handleSelectEvent}
                        onSelectSlot={handleSelectSlot}
                        selectable
                        popup
                        eventPropGetter={getEventStyleGetter}
                        messages={{
                            today: 'Hoy',
                            previous: 'Anterior',
                            next: 'Siguiente',
                            month: 'Mes',
                            week: 'Semana',
                            day: 'Día',
                            agenda: 'Agenda',
                            date: 'Fecha',
                            time: 'Hora',
                            event: 'Evento',
                            allDay: 'Todo el día',
                        }}
                    />
                </div>
            </Card>

            {/* Detalle Cita Seleccionada */}
            {selectedCita && (
                <Card className={theme === 'dark' ? 'dark' : ''}>
                    <div className="p-6">
                        <h3 className={`text-lg font-bold mb-4 ${
                            theme === 'dark' ? 'text-white' : 'text-gray-900'
                        }`}>
                            Detalles de Cita
                        </h3>

                        <div className="space-y-3 mb-4">
                            <div className="flex items-center gap-3">
                                <User className="w-5 h-5 text-blue-500" />
                                <div>
                                    <p className={`text-sm font-medium ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-600'
                                    }`}>
                                        Paciente
                                    </p>
                                    <p className={theme === 'dark' ? 'text-white' : 'text-gray-900'}>
                                        {selectedCita.paciente?.nombre}
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-center gap-3">
                                <User className="w-5 h-5 text-green-500" />
                                <div>
                                    <p className={`text-sm font-medium ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-600'
                                    }`}>
                                        Fisioterapeuta
                                    </p>
                                    <p className={theme === 'dark' ? 'text-white' : 'text-gray-900'}>
                                        {selectedCita.fisioterapeuta?.nombre}
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-center gap-3">
                                <Clock className="w-5 h-5 text-purple-500" />
                                <div>
                                    <p className={`text-sm font-medium ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-600'
                                    }`}>
                                        Hora
                                    </p>
                                    <p className={theme === 'dark' ? 'text-white' : 'text-gray-900'}>
                                        {format(new Date(selectedCita.fecha_hora), 'HH:mm')}
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-center gap-3">
                                <MapPin className="w-5 h-5 text-red-500" />
                                <div>
                                    <p className={`text-sm font-medium ${
                                        theme === 'dark' ? 'text-gray-300' : 'text-gray-600'
                                    }`}>
                                        Sala
                                    </p>
                                    <p className={theme === 'dark' ? 'text-white' : 'text-gray-900'}>
                                        {selectedCita.sala?.nombre}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {selectedCita.notas && (
                            <div className={`mb-4 p-3 rounded text-sm ${
                                theme === 'dark'
                                    ? 'bg-gray-700 text-gray-300'
                                    : 'bg-gray-50 text-gray-700'
                            }`}>
                                <p className="font-medium mb-1">Notas:</p>
                                <p>{selectedCita.notas}</p>
                            </div>
                        )}

                        <div className="flex gap-3">
                            <Button
                                variant="outline"
                                className="flex-1 flex items-center justify-center gap-2"
                                onClick={() => {
                                    // Aquí iría la lógica de edición
                                }}
                            >
                                <Edit2 className="w-4 h-4" />
                                Editar
                            </Button>
                            <Button
                                className="flex-1"
                                onClick={() => setSelectedCita(null)}
                            >
                                Cerrar
                            </Button>
                        </div>
                    </div>
                </Card>
            )}

            {/* Leyenda de Estados */}
            <Card className={theme === 'dark' ? 'dark' : ''}>
                <div className="p-4">
                    <h4 className={`font-semibold mb-3 ${
                        theme === 'dark' ? 'text-white' : 'text-gray-900'
                    }`}>
                        Estados
                    </h4>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                        {[
                            { label: 'Pendiente', color: '#f59e0b' },
                            { label: 'Confirmada', color: '#10b981' },
                            { label: 'Completada', color: '#6366f1' },
                            { label: 'Cancelada', color: '#ef4444' },
                        ].map(({ label, color }) => (
                            <div key={label} className="flex items-center gap-2">
                                <div
                                    className="w-4 h-4 rounded"
                                    style={{ backgroundColor: color }}
                                />
                                <span className={theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}>
                                    {label}
                                </span>
                            </div>
                        ))}
                    </div>
                </div>
            </Card>
        </div>
    );
}
