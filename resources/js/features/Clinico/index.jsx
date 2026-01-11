import React, { useState } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../../components/ui/Tabs';
import { useTheme } from '../../components/theme-provider';
import { FileText, Activity } from 'lucide-react';
import HistoriaClinicaView from './HistoriaClinicaView';
import SesionesView from './SesionesView';

export default function ClinicoPage() {
    const { theme } = useTheme();
    const [activeTab, setActiveTab] = useState('historias');

    return (
        <div className={`space-y-6 ${theme === 'dark' ? 'dark' : ''}`}>
            {/* Header */}
            <div className="mb-8">
                <h1 className={`text-4xl font-bold ${
                    theme === 'dark' ? 'text-white' : 'text-gray-900'
                }`}>
                    Gestión Clínica
                </h1>
                <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                    Administra historias clínicas y sesiones de tratamiento
                </p>
            </div>

            {/* Tabs */}
            <Tabs value={activeTab} onValueChange={setActiveTab}>
                <TabsList className={theme === 'dark' ? 'dark' : ''}>
                    <TabsTrigger value="historias" className="flex items-center gap-2">
                        <FileText className="w-4 h-4" />
                        Historias Clínicas
                    </TabsTrigger>
                    <TabsTrigger value="sesiones" className="flex items-center gap-2">
                        <Activity className="w-4 h-4" />
                        Sesiones
                    </TabsTrigger>
                </TabsList>

                {/* Historias */}
                <TabsContent value="historias" className="mt-6">
                    <HistoriaClinicaView />
                </TabsContent>

                {/* Sesiones */}
                <TabsContent value="sesiones" className="mt-6">
                    <SesionesView />
                </TabsContent>
            </Tabs>
        </div>
    );
}
