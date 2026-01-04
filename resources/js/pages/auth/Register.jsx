import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { GoogleLogin } from '@react-oauth/google';
import { authService } from '../../services/authService';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { ShieldCheck, Mail, Eye, EyeOff, RefreshCw, AlertCircle, User, Phone, MapPin, Calendar, Building2, CreditCard } from 'lucide-react';
import AuthLayout from '../../layouts/AuthLayout';
import { motion, AnimatePresence } from 'framer-motion';

export default function Register() {
    const navigate = useNavigate();
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    const [isDuplicate, setIsDuplicate] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    
    // State for Person Type
    const [tipoPersona, setTipoPersona] = useState('PACIENTE-NATURAL'); 

    // Form State
    const [formData, setFormData] = useState({
        name: '', 
        lastname: '', 
        documento_tipo: 'DNI',
        documento_numero: '',
        fecha_nacimiento: '',
        telefono: '',
        direccion: '',
        email: '',
        password: '',
        password_confirmation: ''
    });

    // Security States
    const [ageError, setAgeError] = useState(false);
    const [passwordStrength, setPasswordStrength] = useState(0);

    const handleTypeChange = (type) => {
        setTipoPersona(type);
        setFormData(prev => ({
            ...prev,
            documento_tipo: type === 'PACIENTE-JURIDICA' ? 'RUC' : 'DNI',
            documento_numero: '',
            lastname: '',
            fecha_nacimiento: ''
        }));
        setError('');
    };

    // Calculate Age
    useEffect(() => {
        if (tipoPersona === 'PACIENTE-NATURAL' && formData.fecha_nacimiento) {
            const birthDate = new Date(formData.fecha_nacimiento);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
            setAgeError(age < 18);
        } else {
            setAgeError(false);
        }
    }, [formData.fecha_nacimiento, tipoPersona]);

    // Check Password Strength
    useEffect(() => {
        const pass = formData.password;
        let score = 0;
        if (!pass) return setPasswordStrength(0);
        if (pass.length >= 8) score++;
        if (/[A-Z]/.test(pass)) score++;
        if (/[0-9]/.test(pass)) score++;
        if (/[@$!%*?&]/.test(pass)) score++;
        setPasswordStrength(score);
    }, [formData.password]);

    const generatePassword = () => {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$!%*?&";
        let password = "";
        for (let i = 0; i < 12; i++) password += chars.charAt(Math.floor(Math.random() * chars.length));
        password += "A1@"; 
        setFormData(prev => ({ ...prev, password: password, password_confirmation: password }));
    };

    const handleGoogleSuccess = async (credentialResponse) => {
        if (ageError && tipoPersona === 'PACIENTE-NATURAL') return setError('Debes ser mayor de 18 años.');
        setIsLoading(true);
        setError('');
        try {
            await authService.loginWithGoogle(credentialResponse.credential);
            navigate('/');
        } catch (err) {
            const msg = err.response?.data?.message;
            if (msg === 'Este correo o DNI ya está registrado.') setIsDuplicate(true);
            else setError('No pudimos registrarte con Google. Intenta nuevamente.');
        } finally {
            setIsLoading(false);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        if (tipoPersona === 'PACIENTE-NATURAL' && ageError) return setError('Debes ser mayor de 18 años para registrarte.');
        if (formData.password !== formData.password_confirmation) return setError('Las contraseñas no coinciden.');
        if (passwordStrength < 3) return setError('La contraseña es muy débil. Usa mayúsculas y símbolos.');

        setIsLoading(true);
        try {
            const payload = {
                tipo_persona: tipoPersona,
                name: tipoPersona === 'PACIENTE-NATURAL' ? `${formData.name} ${formData.lastname}` : formData.name,
                email: formData.email,
                password: formData.password,
                password_confirmation: formData.password_confirmation,
                telefono: formData.telefono,
                direccion: formData.direccion,
                documento_tipo: formData.documento_tipo,
                documento_numero: formData.documento_numero,
                nombres: formData.name,
            };

            if (tipoPersona === 'PACIENTE-NATURAL') {
                payload.apellidos = formData.lastname;
                payload.fecha_nacimiento = formData.fecha_nacimiento;
            }

            await authService.register(payload);
            navigate('/login'); 
        } catch (err) {
            console.error(err);
            const errors = err.response?.data?.errors || {};
            const msg = err.response?.data?.message || '';
            
            const isEmailDup = errors.email?.some(e => e.includes('registrado') || e.includes('taken'));
            const isDocDup = errors.documento_numero?.some(e => e.includes('registrado') || e.includes('taken') || e.includes('key'));
            
            if (isEmailDup || isDocDup || msg.includes('ya está registrado')) {
                setIsDuplicate(true);
            } else {
                setError(msg || 'Error en el registro. Verifica tus datos.');
            }
        } finally {
            setIsLoading(false);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    if (isDuplicate) {
        return (
            <AuthLayout title="¡Cuenta Existente!" subtitle="Tus datos ya están en nuestro sistema">
                <div className="text-center space-y-6">
                    <motion.div 
                        initial={{ scale: 0.8 }} animate={{ scale: 1 }}
                        className="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto shadow-inner"
                    >
                        <ShieldCheck className="w-10 h-10 text-yellow-600" />
                    </motion.div>
                    <p className="text-gray-600">El correo o documento que ingresaste ya está registrado.</p>
                    <div className="grid gap-3">
                        <Button className="w-full shadow-lg shadow-blue-500/20" onClick={() => navigate('/login')}>Iniciar Sesión</Button>
                        <Button variant="outline" onClick={() => navigate('/forgot-password')}>Recuperar Contraseña</Button>
                    </div>
                    <Button variant="ghost" size="sm" onClick={() => setIsDuplicate(false)}>Intentar registro nuevamente</Button>
                </div>
            </AuthLayout>
        );
    }

    return (
        <AuthLayout 
            title="Crear Cuenta Nueva" 
            subtitle="Únete a nuestra plataforma de fisioterapia"
            cardClassName="max-w-4xl" // Wider Layout
        >
            <AnimatePresence>
                {error && (
                    <motion.div 
                        initial={{ opacity: 0, height: 0 }} animate={{ opacity: 1, height: 'auto' }} exit={{ opacity: 0, height: 0 }}
                        className="mb-6 p-4 bg-red-50/80 backdrop-blur-sm border border-red-200/50 rounded-xl flex items-center gap-3 text-red-700 shadow-sm"
                    >
                        <AlertCircle className="h-5 w-5 shrink-0" />
                        <span className="font-medium text-sm">{error}</span>
                    </motion.div>
                )}
            </AnimatePresence>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                {/* Left Column: Personal Data */}
                <div className="space-y-6">
                    <div className="flex items-center gap-2 text-blue-600 font-bold border-b border-gray-100 pb-2">
                        <User className="w-5 h-5" />
                        <span className="uppercase tracking-wide text-xs">Datos Personales</span>
                    </div>

                    {/* Type Selector */}
                    <div className="flex p-1.5 bg-gray-100/80 rounded-xl">
                        <button
                            type="button"
                            onClick={() => handleTypeChange('PACIENTE-NATURAL')}
                            className={`flex-1 py-2 text-sm font-semibold rounded-lg transition-all ${
                                tipoPersona === 'PACIENTE-NATURAL' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700'
                            }`}
                        >
                            Persona Natural
                        </button>
                        <button
                            type="button"
                            onClick={() => handleTypeChange('PACIENTE-JURIDICA')}
                            className={`flex-1 py-2 text-sm font-semibold rounded-lg transition-all ${
                                tipoPersona === 'PACIENTE-JURIDICA' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700'
                            }`}
                        >
                            Empresa
                        </button>
                    </div>
                    
                    <form id="register-form" onSubmit={handleSubmit} className="space-y-4">
                        {tipoPersona === 'PACIENTE-NATURAL' ? (
                            <>
                                <div className="grid grid-cols-12 gap-3">
                                    <div className="col-span-4">
                                         <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-1 mb-1.5">Documento</label>
                                         <div className="relative">
                                            <select
                                                name="documento_tipo"
                                                value={formData.documento_tipo}
                                                onChange={handleChange}
                                                className="w-full h-11 rounded-xl border border-gray-200 bg-gray-50/50 px-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                            >
                                                <option value="DNI">DNI</option>
                                                <option value="CARNET_EXT">C.E.</option>
                                            </select>
                                         </div>
                                    </div>
                                    <div className="col-span-8">
                                         <Input
                                            name="documento_numero"
                                            label="Número"
                                            placeholder="12345678"
                                            maxLength={formData.documento_tipo === 'DNI' ? 8 : 9}
                                            value={formData.documento_numero}
                                            onChange={handleChange}
                                            required
                                            icon={<CreditCard className="text-gray-400" />}
                                        />
                                    </div>
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <Input label="Nombres" name="name" placeholder="Juan" value={formData.name} onChange={handleChange} required />
                                    <Input label="Apellidos" name="lastname" placeholder="Pérez" value={formData.lastname} onChange={handleChange} required />
                                </div>
                                <div>
                                    <Input 
                                        label="Fecha Nacimiento" 
                                        name="fecha_nacimiento" 
                                        type="date" 
                                        value={formData.fecha_nacimiento} 
                                        onChange={handleChange} 
                                        required 
                                        error={ageError ? "Mayor de 18 años requerido" : null}
                                        icon={<Calendar className="text-gray-400" />}
                                    />
                                </div>
                            </>
                        ) : (
                            <>
                                <Input
                                    label="RUC (11 dígitos)"
                                    name="documento_numero"
                                    placeholder="20100000001"
                                    maxLength={11}
                                    value={formData.documento_numero}
                                    onChange={handleChange}
                                    required
                                    icon={<Building2 className="text-gray-400" />}
                                />
                                <Input
                                    label="Razón Social"
                                    name="name"
                                    placeholder="Mi Empresa S.A.C."
                                    value={formData.name}
                                    onChange={handleChange}
                                    required
                                />
                            </>
                        )}

                        <Input label="Teléfono / Celular" name="telefono" type="tel" placeholder="999 999 999" value={formData.telefono} onChange={handleChange} required icon={<Phone className="text-gray-400" />} />
                        <Input label="Dirección Completa" name="direccion" placeholder="Av. Principal 123" value={formData.direccion} onChange={handleChange} required icon={<MapPin className="text-gray-400" />} />
                    </form>
                </div>

                {/* Right Column: Secure Data */}
                <div className="space-y-6">
                    <div className="flex items-center gap-2 text-blue-600 font-bold border-b border-gray-100 pb-2">
                        <Mail className="w-5 h-5" />
                        <span className="uppercase tracking-wide text-xs">Acceso Seguro</span>
                    </div>

                    <div className="space-y-5">
                        <Input label="Email Profesional" name="email" type="email" placeholder="contacto@ejemplo.com" value={formData.email} onChange={handleChange} required form="register-form" autoComplete="username" icon={<Mail className="text-gray-400" />} />

                        <div className="space-y-3">
                            <div className="flex justify-between items-end">
                                <label className="text-sm font-medium text-gray-700 ml-1">Contraseña</label>
                                <div className="flex gap-3">
                                    <button type="button" onClick={() => setShowPassword(!showPassword)} className="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1 font-semibold transition-colors">
                                        {showPassword ? <EyeOff className="w-3.5 h-3.5" /> : <Eye className="w-3.5 h-3.5" />}
                                        {showPassword ? 'Ocultar' : 'Ver'}
                                    </button>
                                    <button type="button" onClick={generatePassword} className="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1 font-semibold transition-colors"><RefreshCw className="w-3.5 h-3.5" /> Generar</button>
                                </div>
                            </div>
                            <Input 
                                name="password" 
                                type={showPassword ? "text" : "password"} 
                                placeholder="••••••••" 
                                value={formData.password} 
                                onChange={handleChange} 
                                required 
                                form="register-form" 
                                autoComplete="new-password" 
                            />
                            {/* Strength Meter */}
                            {formData.password && (
                                <div className="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <motion.div 
                                        initial={{ width: 0 }}
                                        animate={{ width: passwordStrength === 1 ? '33%' : passwordStrength === 2 ? '66%' : '100%' }}
                                        className={`h-full transition-colors duration-300 ${passwordStrength <= 2 ? 'bg-red-500' : passwordStrength === 3 ? 'bg-yellow-500' : 'bg-green-500'}`} 
                                    />
                                </div>
                            )}
                        </div>

                        <Input 
                            label="Confirmar Contraseña" 
                            name="password_confirmation" 
                            type={showPassword ? "text" : "password"} 
                            placeholder="••••••••" 
                            value={formData.password_confirmation} 
                            onChange={handleChange} 
                            required 
                            form="register-form" 
                            autoComplete="new-password"
                        />

                        <Button 
                            type="submit" 
                            className="w-full mt-4 text-lg shadow-xl shadow-blue-500/20" 
                            size="lg"
                            isLoading={isLoading} 
                            form="register-form" 
                            disabled={ageError && tipoPersona === 'PACIENTE-NATURAL'}
                        >
                            Crear Cuenta
                        </Button>

                        <div className="relative my-8">
                            <div className="absolute inset-0 flex items-center">
                                <span className="w-full border-t border-gray-200" />
                            </div>
                            <div className="relative flex justify-center text-xs uppercase">
                                <span className="bg-white/0 backdrop-blur-md px-2 text-gray-500 font-medium">O regístrate con Google</span>
                            </div>
                        </div>
                        
                        <div className="flex justify-center">
                            <div className="transform transition-transform hover:scale-105 active:scale-95 duration-200">
                                <GoogleLogin onSuccess={handleGoogleSuccess} onError={() => setError('Falló Google Auth')} theme="filled_blue" shape="pill" width="300" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="text-center mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                <span className="text-gray-500">¿Ya tienes cuenta? </span>
                <Link to="/login" className="font-bold text-blue-600 hover:text-blue-500 hover:underline transition-all">Inicia Sesión aquí</Link>
            </div>
        </AuthLayout>
    );
}
