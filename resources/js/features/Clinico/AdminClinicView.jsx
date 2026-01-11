import React, { useState } from 'react';
import { Plus, Edit2, Trash2, AlertCircle, Loader2, X } from 'lucide-react';
import { useGetHistorias, useCreateHistoria, useUpdateHistoria, useDeleteHistoria, useGetSesiones, useCreateSesion, useUpdateSesion, useDeleteSesion } from '../../hooks/useClinico';
import { useGetPersonas, useGetFisioterapeutas } from '../../hooks/usePrincipal';
import { useTheme } from '../../components/theme-provider';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import { Button } from '../../components/ui/Button';
import { Card } from '../../components/ui/Card';
import { Input } from '../../components/ui/Input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../../components/ui/Tabs';

export default function AdminClinicView() {
    const { theme } = useTheme();
    const [activeTab, setActiveTab] = useState('historias');
    const [editingId, setEditingId] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [filterPaciente, setFilterPaciente] = useState('');

    // Queries
    const { data: historiasData, isLoading: loadingHistorias } = useGetHistorias({ paciente_id: filterPaciente });
    const { data: sesionesData, isLoading: loadingSesiones } = useGetSesiones({ paciente_id: filterPaciente });
    const { data: personasData } = useGetPersonas();
    const { data: fisioterapeutasData } = useGetFisioterapeutas();

    // Mutations
    const { mutate: createHistoria, isPending: creatingHistoria } = useCreateHistoria();
    const { mutate: updateHistoria, isPending: updatingHistoria } = useUpdateHistoria();
    const { mutate: deleteHistoria } = useDeleteHistoria();
    const { mutate: createSesion, isPending: creatingSesion } = useCreateSesion();
    const { mutate: updateSesion, isPending: updatingSesion } = useUpdateSesion();
    const { mutate: deleteSesion } = useDeleteSesion();

    // Form State
    const [historiaForm, setHistoriaForm] = useState({
        persona_id: '',
        motivo_consulta: '',
        antecedentes: '',
        alergias: '',
        diagnostico_inicial: '',
        recomendaciones: ''
    });

    const [sesionForm, setSesionForm] = useState({
        paciente_id: '',
        fisioterapeuta_id: '',
        servicio_id: '',
        fecha_atencion: '',
        duracion_minutos: '',
        notas: '',
        ejercicios_realizados: '',
        materiales_usados: ''
    });

    const historias = historiasData?.data || [];
    const sesiones = sesionesData?.data || [];
    const personas = personasData?.data || [];
    const fisioterapeutas = fisioterapeutasData?.data || [];

    const handleSaveHistoria = () => {
        if (editingId) {
            updateHistoria({ id: editingId, ...historiaForm });
        } else {
            createHistoria(historiaForm);
        }
        resetHistoriaForm();
        setShowModal(false);
    };

    const handleSaveSesion = () => {
        if (editingId) {
            updateSesion({ id: editingId, ...sesionForm });
        } else {
            createSesion(sesionForm);
        }
        resetSesionForm();
        setShowModal(false);
    };

    const resetHistoriaForm = () => {
        setHistoriaForm({
            persona_id: '',
            motivo_consulta: '',
            antecedentes: '',
            alergias: '',
            diagnostico_inicial: '',
            recomendaciones: ''
        });
        setEditingId(null);
    };

    const resetSesionForm = () => {
        setSesionForm({
            paciente_id: '',
            fisioterapeuta_id: '',
            servicio_id: '',
            fecha_atencion: '',
            duracion_minutos: '',
            notas: '',
            ejercicios_realizados: '',
            materiales_usados: ''
        });
        setEditingId(null);
    };

    const openHistoriaModal = (historia = null) => {
        if (historia) {
            setHistoriaForm(historia);
            setEditingId(historia.historia_id);
        }
        setShowModal(true);
    };

    const openSesionModal = (sesion = null) => {
        if (sesion) {
            setSesionForm(sesion);
            setEditingId(sesion.sesion_id);
        }
        setShowModal(true);
    };

    return (
        <div className={`min-h-screen ${theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50'} p-6`}>
            <div className="max-w-7xl mx-auto">
                {/* Header */}
                <div className="mb-8">
                    <h1 className={`text-3xl font-bold mb-2 ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                        ⚙️ Gestión Clínica
                    </h1>
                    <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                        Administra historias clínicas, sesiones y servicios de pacientes
                    </p>
                </div>

                {/* Filter */}
                <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-4 mb-6`}>
                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                        Filtrar por Paciente
                    </label>
                    <select
                        value={filterPaciente}
                        onChange={(e) => setFilterPaciente(e.target.value)}
                        className={`w-full px-4 py-2 rounded-lg border ${
                            theme === 'dark'
                                ? 'bg-gray-700 border-gray-600 text-white'
                                : 'bg-white border-gray-300'
                        } focus:outline-none focus:ring-2 focus:ring-blue-500`}
                    >
                        <option value="">Todos los pacientes</option>
                        {personas.map(p => (
                            <option key={p.persona_id} value={p.persona_id}>
                                {p.nombre}
                            </option>
                        ))}
                    </select>
                </Card>

                {/* Tabs */}
                <Tabs value={activeTab} onValueChange={setActiveTab}>
                    <TabsList className={`grid w-full grid-cols-2 ${theme === 'dark' ? 'bg-gray-800' : 'bg-white'}`}>
                        <TabsTrigger value="historias">Historias Clínicas</TabsTrigger>
                        <TabsTrigger value="sesiones">Sesiones</TabsTrigger>
                    </TabsList>

                    {/* HISTORIAS CLÍNICAS */}
                    <TabsContent value="historias" className="space-y-6">
                        <div className="flex justify-between items-center">
                            <h2 className={`text-2xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                Historias Clínicas
                            </h2>
                            <Button
                                onClick={() => {
                                    resetHistoriaForm();
                                    openHistoriaModal();
                                }}
                                className="flex items-center gap-2"
                            >
                                <Plus size={18} />
                                Nueva Historia
                            </Button>
                        </div>

                        {loadingHistorias ? (
                            <div className="text-center py-8">
                                <Loader2 className={`animate-spin mx-auto ${theme === 'dark' ? 'text-blue-400' : 'text-blue-600'}`} />
                            </div>
                        ) : historias.length > 0 ? (
                            <div className="grid gap-4">
                                {historias.map(historia => (
                                    <Card
                                        key={historia.historia_id}
                                        className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-6`}
                                    >
                                        <div className="flex items-start justify-between mb-4">
                                            <div>
                                                <p className={`font-bold text-lg ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                                    {historia.diagnostico_inicial || 'Sin diagnóstico'}
                                                </p>
                                                <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                    Actualizado: {format(new Date(historia.actualizado_en), 'dd/MM/yyyy', { locale: es })}
                                                </p>
                                            </div>
                                            <div className="flex gap-2">
                                                <Button
                                                    variant="secondary"
                                                    size="sm"
                                                    onClick={() => openHistoriaModal(historia)}
                                                    className="flex items-center gap-2"
                                                >
                                                    <Edit2 size={16} />
                                                    Editar
                                                </Button>
                                                <Button
                                                    variant="danger"
                                                    size="sm"
                                                    onClick={() => deleteHistoria(historia.historia_id)}
                                                    className="flex items-center gap-2"
                                                >
                                                    <Trash2 size={16} />
                                                    Eliminar
                                                </Button>
                                            </div>
                                        </div>

                                        <div className={`grid grid-cols-2 gap-4 p-4 rounded ${theme === 'dark' ? 'bg-gray-700' : 'bg-gray-100'}`}>
                                            {historia.motivo_consulta && (
                                                <div>
                                                    <p className={`text-xs font-semibold ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                        Motivo Consulta
                                                    </p>
                                                    <p className={theme === 'dark' ? 'text-gray-300' : 'text-gray-800'}>
                                                        {historia.motivo_consulta.substring(0, 50)}...
                                                    </p>
                                                </div>
                                            )}
                                            {historia.alergias && (
                                                <div>
                                                    <p className={`text-xs font-semibold ${theme === 'dark' ? 'text-red-400' : 'text-red-600'}`}>
                                                        ⚠️ Alergias
                                                    </p>
                                                    <p className={theme === 'dark' ? 'text-red-300' : 'text-red-800'}>
                                                        {historia.alergias}
                                                    </p>
                                                </div>
                                            )}
                                        </div>
                                    </Card>
                                ))}
                            </div>
                        ) : (
                            <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-8 text-center`}>
                                <AlertCircle className={`mx-auto mb-2 ${theme === 'dark' ? 'text-gray-500' : 'text-gray-400'}`} />
                                <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                                    No hay historias clínicas registradas
                                </p>
                            </Card>
                        )}
                    </TabsContent>

                    {/* SESIONES */}
                    <TabsContent value="sesiones" className="space-y-6">
                        <div className="flex justify-between items-center">
                            <h2 className={`text-2xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                Sesiones
                            </h2>
                            <Button
                                onClick={() => {
                                    resetSesionForm();
                                    openSesionModal();
                                }}
                                className="flex items-center gap-2"
                            >
                                <Plus size={18} />
                                Nueva Sesión
                            </Button>
                        </div>

                        {loadingSesiones ? (
                            <div className="text-center py-8">
                                <Loader2 className={`animate-spin mx-auto ${theme === 'dark' ? 'text-blue-400' : 'text-blue-600'}`} />
                            </div>
                        ) : sesiones.length > 0 ? (
                            <div className="grid gap-4">
                                {sesiones.map(sesion => (
                                    <Card
                                        key={sesion.sesion_id}
                                        className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-6`}
                                    >
                                        <div className="flex items-start justify-between mb-4">
                                            <div>
                                                <p className={`font-bold text-lg ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                                    Sesión - {format(new Date(sesion.fecha_atencion), 'dd/MM/yyyy', { locale: es })}
                                                </p>
                                                <p className={`text-sm ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                    Duración: {sesion.duracion_minutos} minutos
                                                </p>
                                            </div>
                                            <div className="flex gap-2">
                                                <Button
                                                    variant="secondary"
                                                    size="sm"
                                                    onClick={() => openSesionModal(sesion)}
                                                    className="flex items-center gap-2"
                                                >
                                                    <Edit2 size={16} />
                                                    Editar
                                                </Button>
                                                <Button
                                                    variant="danger"
                                                    size="sm"
                                                    onClick={() => deleteSesion(sesion.sesion_id)}
                                                    className="flex items-center gap-2"
                                                >
                                                    <Trash2 size={16} />
                                                    Eliminar
                                                </Button>
                                            </div>
                                        </div>

                                        <div className={`grid grid-cols-3 gap-4 p-4 rounded ${theme === 'dark' ? 'bg-gray-700' : 'bg-gray-100'}`}>
                                            <div>
                                                <p className={`text-xs font-semibold ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                    Notas
                                                </p>
                                                <p className={theme === 'dark' ? 'text-gray-300' : 'text-gray-800'}>
                                                    {sesion.notas?.substring(0, 40) || '-'}
                                                </p>
                                            </div>
                                            <div>
                                                <p className={`text-xs font-semibold ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                    Ejercicios
                                                </p>
                                                <p className={theme === 'dark' ? 'text-gray-300' : 'text-gray-800'}>
                                                    {sesion.ejercicios_realizados?.substring(0, 40) || '-'}
                                                </p>
                                            </div>
                                            <div>
                                                <p className={`text-xs font-semibold ${theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}`}>
                                                    Materiales
                                                </p>
                                                <p className={theme === 'dark' ? 'text-gray-300' : 'text-gray-800'}>
                                                    {sesion.materiales_usados?.substring(0, 40) || '-'}
                                                </p>
                                            </div>
                                        </div>
                                    </Card>
                                ))}
                            </div>
                        ) : (
                            <Card className={`${theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white'} p-8 text-center`}>
                                <AlertCircle className={`mx-auto mb-2 ${theme === 'dark' ? 'text-gray-500' : 'text-gray-400'}`} />
                                <p className={theme === 'dark' ? 'text-gray-400' : 'text-gray-600'}>
                                    No hay sesiones registradas
                                </p>
                            </Card>
                        )}
                    </TabsContent>
                </Tabs>

                {/* Modal Historias */}
                {showModal && activeTab === 'historias' && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <Card className={`${theme === 'dark' ? 'bg-gray-800' : 'bg-white'} p-6 max-w-2xl w-full max-h-screen overflow-y-auto`}>
                            <div className="flex items-center justify-between mb-6">
                                <h3 className={`text-xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    {editingId ? 'Editar Historia' : 'Nueva Historia Clínica'}
                                </h3>
                                <button
                                    onClick={() => setShowModal(false)}
                                    className={`p-1 rounded ${theme === 'dark' ? 'hover:bg-gray-700' : 'hover:bg-gray-100'}`}
                                >
                                    <X size={20} />
                                </button>
                            </div>

                            <div className="space-y-4">
                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Paciente *
                                    </label>
                                    <select
                                        value={historiaForm.persona_id}
                                        onChange={(e) => setHistoriaForm({ ...historiaForm, persona_id: e.target.value })}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                    >
                                        <option value="">Seleccionar paciente</option>
                                        {personas.map(p => (
                                            <option key={p.persona_id} value={p.persona_id}>
                                                {p.nombre}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Motivo de Consulta
                                    </label>
                                    <textarea
                                        value={historiaForm.motivo_consulta}
                                        onChange={(e) => setHistoriaForm({ ...historiaForm, motivo_consulta: e.target.value })}
                                        rows={3}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                        placeholder="Describe el motivo de la consulta..."
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Antecedentes
                                    </label>
                                    <textarea
                                        value={historiaForm.antecedentes}
                                        onChange={(e) => setHistoriaForm({ ...historiaForm, antecedentes: e.target.value })}
                                        rows={3}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                        placeholder="Antecedentes médicos..."
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        ⚠️ Alergias
                                    </label>
                                    <Input
                                        value={historiaForm.alergias}
                                        onChange={(e) => setHistoriaForm({ ...historiaForm, alergias: e.target.value })}
                                        placeholder="Alergias conocidas..."
                                        className={theme === 'dark' ? 'bg-gray-700 border-gray-600 text-white' : ''}
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Diagnóstico Inicial *
                                    </label>
                                    <textarea
                                        value={historiaForm.diagnostico_inicial}
                                        onChange={(e) => setHistoriaForm({ ...historiaForm, diagnostico_inicial: e.target.value })}
                                        rows={3}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                        placeholder="Diagnóstico inicial..."
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Recomendaciones
                                    </label>
                                    <textarea
                                        value={historiaForm.recomendaciones}
                                        onChange={(e) => setHistoriaForm({ ...historiaForm, recomendaciones: e.target.value })}
                                        rows={3}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                        placeholder="Recomendaciones del tratamiento..."
                                    />
                                </div>
                            </div>

                            <div className="flex gap-3 mt-6">
                                <Button
                                    onClick={handleSaveHistoria}
                                    isLoading={creatingHistoria || updatingHistoria}
                                >
                                    {editingId ? 'Actualizar' : 'Crear'} Historia
                                </Button>
                                <Button
                                    variant="secondary"
                                    onClick={() => setShowModal(false)}
                                >
                                    Cancelar
                                </Button>
                            </div>
                        </Card>
                    </div>
                )}

                {/* Modal Sesiones */}
                {showModal && activeTab === 'sesiones' && (
                    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <Card className={`${theme === 'dark' ? 'bg-gray-800' : 'bg-white'} p-6 max-w-2xl w-full max-h-screen overflow-y-auto`}>
                            <div className="flex items-center justify-between mb-6">
                                <h3 className={`text-xl font-bold ${theme === 'dark' ? 'text-white' : 'text-gray-900'}`}>
                                    {editingId ? 'Editar Sesión' : 'Nueva Sesión'}
                                </h3>
                                <button
                                    onClick={() => setShowModal(false)}
                                    className={`p-1 rounded ${theme === 'dark' ? 'hover:bg-gray-700' : 'hover:bg-gray-100'}`}
                                >
                                    <X size={20} />
                                </button>
                            </div>

                            <div className="space-y-4">
                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Paciente *
                                    </label>
                                    <select
                                        value={sesionForm.paciente_id}
                                        onChange={(e) => setSesionForm({ ...sesionForm, paciente_id: e.target.value })}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                    >
                                        <option value="">Seleccionar paciente</option>
                                        {personas.map(p => (
                                            <option key={p.persona_id} value={p.persona_id}>
                                                {p.nombre}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                            Fisioterapeuta
                                        </label>
                                        <select
                                            value={sesionForm.fisioterapeuta_id}
                                            onChange={(e) => setSesionForm({ ...sesionForm, fisioterapeuta_id: e.target.value })}
                                            className={`w-full px-4 py-2 rounded-lg border ${
                                                theme === 'dark'
                                                    ? 'bg-gray-700 border-gray-600 text-white'
                                                    : 'bg-white border-gray-300'
                                            }`}
                                        >
                                            <option value="">Seleccionar</option>
                                            {fisioterapeutas.map(f => (
                                                <option key={f.persona_id} value={f.persona_id}>
                                                    {f.nombre}
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                            Duración (minutos) *
                                        </label>
                                        <Input
                                            type="number"
                                            value={sesionForm.duracion_minutos}
                                            onChange={(e) => setSesionForm({ ...sesionForm, duracion_minutos: e.target.value })}
                                            placeholder="60"
                                            className={theme === 'dark' ? 'bg-gray-700 border-gray-600 text-white' : ''}
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Fecha y Hora *
                                    </label>
                                    <Input
                                        type="datetime-local"
                                        value={sesionForm.fecha_atencion}
                                        onChange={(e) => setSesionForm({ ...sesionForm, fecha_atencion: e.target.value })}
                                        className={theme === 'dark' ? 'bg-gray-700 border-gray-600 text-white' : ''}
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Notas
                                    </label>
                                    <textarea
                                        value={sesionForm.notas}
                                        onChange={(e) => setSesionForm({ ...sesionForm, notas: e.target.value })}
                                        rows={3}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                        placeholder="Observaciones de la sesión..."
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Ejercicios Realizados
                                    </label>
                                    <textarea
                                        value={sesionForm.ejercicios_realizados}
                                        onChange={(e) => setSesionForm({ ...sesionForm, ejercicios_realizados: e.target.value })}
                                        rows={3}
                                        className={`w-full px-4 py-2 rounded-lg border ${
                                            theme === 'dark'
                                                ? 'bg-gray-700 border-gray-600 text-white'
                                                : 'bg-white border-gray-300'
                                        }`}
                                        placeholder="Ejercicios realizados en la sesión..."
                                    />
                                </div>

                                <div>
                                    <label className={`block text-sm font-semibold mb-2 ${theme === 'dark' ? 'text-gray-300' : 'text-gray-700'}`}>
                                        Materiales Usados
                                    </label>
                                    <Input
                                        value={sesionForm.materiales_usados}
                                        onChange={(e) => setSesionForm({ ...sesionForm, materiales_usados: e.target.value })}
                                        placeholder="Ej: Balón, bandas elásticas, etc..."
                                        className={theme === 'dark' ? 'bg-gray-700 border-gray-600 text-white' : ''}
                                    />
                                </div>
                            </div>

                            <div className="flex gap-3 mt-6">
                                <Button
                                    onClick={handleSaveSesion}
                                    isLoading={creatingSesion || updatingSesion}
                                >
                                    {editingId ? 'Actualizar' : 'Crear'} Sesión
                                </Button>
                                <Button
                                    variant="secondary"
                                    onClick={() => setShowModal(false)}
                                >
                                    Cancelar
                                </Button>
                            </div>
                        </Card>
                    </div>
                )}
            </div>
        </div>
    );
}
