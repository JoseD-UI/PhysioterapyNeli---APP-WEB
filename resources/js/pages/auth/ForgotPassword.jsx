import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { authService } from '../../services/authService';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/Card';
import { AlertCircle, CheckCircle, Mail } from 'lucide-react';

export default function ForgotPassword() {
    const [email, setEmail] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setIsLoading(true);

        try {
            await authService.forgotPassword(email);
            setIsSuccess(true);
        } catch (err) {
            setError(err.response?.data?.message || err.response?.data?.email?.[0] || 'No pudimos encontrar un usuario con ese correo.');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="min-h-[80vh] flex items-center justify-center px-4 py-12">
            <Card className="w-full max-w-md shadow-xl border-gray-100">
                <CardHeader className="text-center space-y-2">
                     <div className="w-12 h-12 bg-blue-100 rounded-lg mx-auto flex items-center justify-center text-blue-600 font-bold text-xl">
                        <Mail className="w-6 h-6" />
                    </div>
                    <CardTitle className="text-2xl font-bold text-gray-900">Recuperar Contraseña</CardTitle>
                    <p className="text-sm text-gray-500">
                        Ingresa tu email y te enviaremos un enlace para restablecerla.
                    </p>
                </CardHeader>
                <CardContent className="space-y-6">
                    {error && (
                        <div className="p-3 bg-red-50 border border-red-200 rounded-md flex items-center gap-2 text-sm text-red-600 animate-in fade-in">
                            <AlertCircle className="h-4 w-4 shrink-0" />
                            {error}
                        </div>
                    )}

                    {isSuccess ? (
                         <div className="p-6 bg-green-50 border border-green-100 rounded-lg text-center space-y-4 animate-in fade-in">
                            <CheckCircle className="h-12 w-12 text-green-500 mx-auto" />
                            <div className="space-y-1">
                                <h3 className="text-lg font-medium text-green-800">¡Enlace Enviado!</h3>
                                <p className="text-sm text-green-700">
                                    Hemos enviado un correo a <strong>{email}</strong> con las instrucciones.
                                </p>
                            </div>
                            <Button variant="outline" className="w-full" onClick={() => setIsSuccess(false)}>
                                Enviar de nuevo
                            </Button>
                        </div>
                    ) : (
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <Input
                                label="Email"
                                type="email"
                                placeholder="tu@email.com"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />

                            <Button type="submit" className="w-full" isLoading={isLoading}>
                                Enviar Enlace de Recuperación
                            </Button>
                        </form>
                    )}

                    <div className="text-center text-sm text-gray-500">
                        <Link to="/login" className="font-medium text-blue-600 hover:text-blue-500">
                            Volver al Inicio de Sesión
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
