import React, { useState } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../../components/ui/Tabs';
import { useTheme } from '../../components/theme-provider';
import { Calendar, List } from 'lucide-react';
import CitasListView from './CitasListView';
import CalendarioView from './CalendarioView';
import CitaFormModal from './CitaFormModal';

export default function AgendaPage() {
    const { theme } = useTheme();
    const [activeTab, setActiveTab] = useState('calendario');
    const [isFormOpen, setIsFormOpen] = useState(false);
    const [selectedCita, setSelectedCita] = useState(null);
    const [defaultDate, setDefaultDate] = useState(null);

    const handleNewCita = (date) => {
        setDefaultDate(date);
        setSelectedCita(null);
        setIsFormOpen(true);
    };

    const handleSelectCita = (cita) => {
        setSelectedCita(cita);
    };

    const handleFormClose = () => {
        setIsFormOpen(false);
        setSelectedCita(null);
        setDefaultDate(null);
    };

    const handleFormSuccess = () => {
        // El query se invalida automáticamente
    };

    return (
        <div className={`space-y-6 ${theme === 'dark' ? 'dark' : ''}`}>
            {/* Header */}
            <div className="mb-8">
                <h1 className={`text-4xl font-bold ${
                    theme === 'dark' ? 'text-white' : 'text-gray-900'
                }`}>
                    Gestión de Agenda
                </h1>
                <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                    Administra citas, horarios y disponibilidad
                </p>
            </div>

            {/* Tabs */}
            <Tabs value={activeTab} onValueChange={setActiveTab}>
                <TabsList className={theme === 'dark' ? 'dark' : ''}>
                    <TabsTrigger value="calendario" className="flex items-center gap-2">
                        <Calendar className="w-4 h-4" />
                        Calendario
                    </TabsTrigger>
                    <TabsTrigger value="lista" className="flex items-center gap-2">
                        <List className="w-4 h-4" />
                        Lista de Citas
                    </TabsTrigger>
                </TabsList>

                {/* Calendario */}
                <TabsContent value="calendario" className="mt-6">
                    <CalendarioView
                        onSelectCita={handleSelectCita}
                        onNewCita={handleNewCita}
                    />
                </TabsContent>

                {/* Lista */}
                <TabsContent value="lista" className="mt-6">
                    <CitasListView />
                </TabsContent>
            </Tabs>

            {/* Modal Formulario */}
            <CitaFormModal
                isOpen={isFormOpen}
                onClose={handleFormClose}
                onSuccess={handleFormSuccess}
                cita={selectedCita}
                defaultDate={defaultDate}
            />
        </div>
    );
}
