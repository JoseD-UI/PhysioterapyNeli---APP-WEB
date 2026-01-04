import React, { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { GoogleLogin } from '@react-oauth/google';
import { AlertCircle, User, Lock } from 'lucide-react';
import AuthLayout from '../../layouts/AuthLayout';
import { motion, AnimatePresence } from 'framer-motion';

export default function Login() {
    const { login, loginGoogle, isLoading, error } = useAuth();
    const navigate = useNavigate();
    const location = useLocation();
    
    const [formData, setFormData] = useState({ email: '', password: '' });
    const [localError, setLocalError] = useState('');

    const from = location.state?.from?.pathname || '/';

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLocalError('');
        try {
            await login(formData.email, formData.password);
            navigate(from, { replace: true });
        } catch (err) {
            console.error(err);
        }
    };

    const handleGoogleSuccess = async (credentialResponse) => {
        try {
            await loginGoogle(credentialResponse.credential);
            navigate(from, { replace: true });
        } catch (err) {
            setLocalError('No pudimos iniciar sesión con Google. Intenta nuevamente.');
        }
    };

    return (
        <AuthLayout 
            title="Bienvenido de nuevo" 
            subtitle="Accede a tu panel de Fisioterapia"
        >
            <div className="space-y-6">
                <AnimatePresence mode="wait">
                    {(error || localError) && (
                        <motion.div 
                            initial={{ opacity: 0, height: 0 }}
                            animate={{ opacity: 1, height: 'auto' }}
                            exit={{ opacity: 0, height: 0 }}
                            className="p-4 bg-red-50/50 backdrop-blur-sm border border-red-200/50 rounded-xl flex items-start gap-3 text-sm text-red-600 shadow-sm"
                        >
                            <AlertCircle className="h-5 w-5 shrink-0 mt-0.5" />
                            <div>
                                <span className="font-semibold block">¡Ups! Algo salió mal.</span>
                                {localError || error}
                            </div>
                        </motion.div>
                    )}
                </AnimatePresence>

                {/* Google Login */}
                <div className="flex justify-center">
                    <div className="transform transition-transform hover:scale-105 active:scale-95 duration-200">
                        <GoogleLogin
                            onSuccess={handleGoogleSuccess}
                            onError={() => setLocalError('Falló el inicio de sesión con Google')}
                            useOneTap
                            shape="pill"
                            theme="filled_blue"
                            text="continue_with"
                            width="280" 
                            containerProps={{
                                style: { display: 'block' }
                            }}
                        />
                    </div>
                </div>

                <div className="relative">
                    <div className="absolute inset-0 flex items-center">
                        <span className="w-full border-t border-gray-200 dark:border-gray-700" />
                    </div>
                    <div className="relative flex justify-center text-xs uppercase">
                        <span className="bg-white/0 backdrop-blur-md px-3 text-gray-500 font-medium tracking-wider">
                            O continúa con email
                        </span>
                    </div>
                </div>

                <motion.form 
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.2 }}
                    onSubmit={handleSubmit} 
                    className="space-y-5"
                >
                    <div className="space-y-4">
                        <Input
                            label="Email Profesional"
                            type="email"
                            placeholder="nombre@ejemplo.com"
                            value={formData.email}
                            onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                            required
                            icon={<User className="text-gray-400" />}
                        />
                        <div>
                            <Input
                                label="Contraseña"
                                type="password"
                                placeholder="••••••••"
                                value={formData.password}
                                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                                required
                                icon={<Lock className="text-gray-400" />}
                            />
                            <div className="flex justify-end mt-1">
                                <Link to="/forgot-password" className="text-xs text-blue-600 hover:text-blue-500 font-medium hover:underline transition-all">
                                    ¿Olvidaste tu contraseña?
                                </Link>
                            </div>
                        </div>
                    </div>
                    
                    <div className="flex items-center gap-2">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            className="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer" 
                        />
                        <label htmlFor="remember" className="text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <Button type="submit" className="w-full text-lg shadow-xl shadow-blue-500/20" size="lg" isLoading={isLoading}>
                        Iniciar Sesión
                    </Button>
                </motion.form>

                <motion.div 
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.3 }}
                    className="text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    ¿Aún no tienes cuenta?{' '}
                    <Link to="/register" className="font-semibold text-blue-600 hover:text-blue-500 hover:underline transition-all">
                        Crear Cuenta Gratis
                    </Link>
                </motion.div>
            </div>
        </AuthLayout>
    );
}
