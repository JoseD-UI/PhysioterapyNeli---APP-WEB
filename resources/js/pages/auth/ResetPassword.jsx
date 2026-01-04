import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { authService } from '../../services/authService';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { AlertCircle, CheckCircle, Lock, Eye, EyeOff, RefreshCw, Mail } from 'lucide-react';
import AuthLayout from '../../layouts/AuthLayout';
import { motion, AnimatePresence } from 'framer-motion';

export default function ResetPassword() {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    
    // Get token and email from URL
    const token = searchParams.get('token');
    const emailParam = searchParams.get('email');

    const [formData, setFormData] = useState({
        email: emailParam || '',
        password: '',
        password_confirmation: ''
    });

    const [isLoading, setIsLoading] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);
    const [error, setError] = useState('');
    const [passwordStrength, setPasswordStrength] = useState(0);
    const [showPassword, setShowPassword] = useState(false);

    // Initial check
    useEffect(() => {
        if (!token) {
            setError('Token inválido o expirado. Solicita un nuevo enlace.');
        }
    }, [token]);

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
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@$!%*?&";
        let password = "";
        const array = new Uint32Array(16);
        window.crypto.getRandomValues(array);
        for (let i = 0; i < 16; i++) {
            password += chars[array[i] % chars.length];
        }
        // Ensure requirements
        if (!/[A-Z]/.test(password)) password += "A";
        if (!/[0-9]/.test(password)) password += "1";
        if (!/[@$!%*?&]/.test(password)) password += "!";
        
        setFormData(prev => ({ ...prev, password, password_confirmation: password }));
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        
        if (!token) return setError('No hay token de recuperación.');
        if (formData.password !== formData.password_confirmation) {
            return setError('Las contraseñas no coinciden.');
        }
        if (passwordStrength < 3) {
            return setError('La contraseña es muy débil. Usa Mayúsculas, Números y Símbolos.');
        }

        setIsLoading(true);

        try {
            await authService.resetPassword({
                token,
                ...formData
            });
            setIsSuccess(true);
            setTimeout(() => navigate('/login'), 4000);
        } catch (err) {
            setError(err.response?.data?.message || err.response?.data?.email?.[0] || 'Error al restablecer contraseña.');
        } finally {
            setIsLoading(false);
        }
    };

    if (isSuccess) {
        return (
            <AuthLayout 
                title="¡Contraseña Restablecida!" 
                subtitle="Tu seguridad ha sido actualizada."
            >
                <motion.div 
                    initial={{ scale: 0.8, opacity: 0 }}
                    animate={{ scale: 1, opacity: 1 }}
                    className="text-center space-y-6"
                >
                    <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        <CheckCircle className="h-10 w-10 text-green-600" />
                    </div>
                    <p className="text-gray-600 text-lg">Tu contraseña ha sido actualizada correctamente.</p>
                    <div className="bg-gray-50 rounded-lg p-3 text-sm text-gray-500">
                        Serás redirigido al login en unos segundos...
                    </div>
                    <Button className="w-full bg-green-600 hover:bg-green-700 shadow-lg shadow-green-500/30" onClick={() => navigate('/login')}>
                        Ir al Login ahora
                    </Button>
                </motion.div>
            </AuthLayout>
        );
    }

    return (
        <AuthLayout 
            title="Nueva Contraseña" 
            subtitle="Crea una contraseña segura para tu cuenta."
        >
            <div className="space-y-6">
                 <AnimatePresence mode="wait">
                    {error && (
                        <motion.div 
                            initial={{ opacity: 0, scale: 0.9 }}
                            animate={{ opacity: 1, scale: 1 }}
                            exit={{ opacity: 0, scale: 0.9 }}
                            className="p-4 bg-red-50/50 backdrop-blur-sm border border-red-200/50 rounded-xl flex items-start gap-3 text-sm text-red-600 shadow-sm"
                        >
                            <AlertCircle className="h-5 w-5 shrink-0 mt-0.5" />
                            <span className="font-medium">{error}</span>
                        </motion.div>
                    )}
                </AnimatePresence>

                <motion.form 
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.2 }}
                    onSubmit={handleSubmit} 
                    className="space-y-5"
                >
                    <Input
                        label="Email"
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        disabled={!!emailParam} // Disabled if comes from URL
                        className={!!emailParam ? "bg-gray-100/50 text-gray-500 cursor-not-allowed opacity-75" : ""}
                        required
                        autoComplete="username"
                        icon={<Mail className="text-gray-400" />}
                    />

                    <div className="space-y-3">
                        <div className="flex justify-between items-end">
                            <label className="text-sm font-medium text-gray-700 ml-1">Nueva Contraseña</label>
                            <div className="flex gap-3">
                                <button type="button" onClick={() => setShowPassword(!showPassword)} className="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1 font-semibold transition-colors">
                                    {showPassword ? <EyeOff className="w-3.5 h-3.5" /> : <Eye className="w-3.5 h-3.5" />}
                                    {showPassword ? 'Ocultar' : 'Ver'}
                                </button>
                                <button type="button" onClick={generatePassword} className="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1 font-semibold transition-colors"><RefreshCw className="w-3.5 h-3.5" /> Generar</button>
                            </div>
                        </div>
                        <Input
                            type={showPassword ? "text" : "password"}
                            name="password"
                            placeholder="••••••••"
                            value={formData.password}
                            onChange={handleChange}
                            required
                            autoComplete="new-password"
                            icon={<Lock className="text-gray-400" />}
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
                        label="Confirmar Password"
                        type={showPassword ? "text" : "password"}
                        name="password_confirmation"
                        placeholder="••••••••"
                        value={formData.password_confirmation}
                        onChange={handleChange}
                        required
                        autoComplete="new-password"
                        icon={<Lock className="text-gray-400" />}
                    />

                    <Button type="submit" className="w-full text-lg shadow-xl shadow-blue-500/20" size="lg" isLoading={isLoading} disabled={!token}>
                        Restablecer Contraseña
                    </Button>
                </motion.form>
            </div>
        </AuthLayout>
    );
}
