import React from 'react';
import { useQuery } from '@tanstack/react-query';
import { publicService } from '../services/publicService';
import { Card, CardContent, CardHeader, CardTitle, CardFooter } from '../components/ui/Card';
import { Button } from '../components/ui/Button';
import { Loader2, AlertCircle, ShoppingCart } from 'lucide-react';
import { useAuth } from '../hooks/useAuth';
import { useNavigate } from 'react-router-dom';

export default function Services() {
    const { isAuthenticated } = useAuth();
    const navigate = useNavigate();

    const { data: services, isLoading, isError } = useQuery({
        queryKey: ['publicServices'],
        queryFn: publicService.getServices
    });

    const handleReserva = (serviceId) => {
        if (!isAuthenticated) {
            // Guardar intención de compra (podríamos usar localStorage)
            navigate('/login', { state: { from: { pathname: '/client/citas/new', search: `?service=${serviceId}` } } });
        } else {
            navigate(`/client/citas/new?service=${serviceId}`);
        }
    };

    if (isLoading) {
        return (
            <div className="flex justify-center items-center h-[60vh]">
                <Loader2 className="h-10 w-10 animate-spin text-blue-600" />
            </div>
        );
    }

    if (isError) {
        return (
            <div className="flex justify-center items-center h-[60vh] text-red-500">
                <AlertCircle className="mr-2" />
                Error al cargar los servicios. Por favor intente más tarde.
            </div>
        );
    }

    return (
        <div className="container mx-auto px-4 py-8">
            <h1 className="text-4xl font-bold text-center mb-4 text-gray-900 dark:text-white">Nuestros Tratamientos</h1>
            <p className="text-center text-gray-600 dark:text-gray-300 mb-12 max-w-2xl mx-auto">
                Selecciona el servicio que necesitas. Nuestros profesionales te guiarán en tu proceso de recuperación.
            </p>

            {services.length === 0 ? (
                <div className="text-center text-gray-500 dark:text-gray-400 py-12">
                    No hay servicios disponibles en este momento.
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {services.map((service) => (
                        <Card key={service.tipo_id} className="hover:shadow-lg transition-shadow duration-300 flex flex-col h-full overflow-hidden border-0 shadow-md">
                            {/* Placeholder Image Logic */}
                            <div className="h-48 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 flex items-center justify-center">
                                {service.imagen_url ? (
                                    <img src={service.imagen_url} alt={service.nombre} className="w-full h-full object-cover" />
                                ) : (
                                    <span className="text-4xl">💆‍♂️</span>
                                )}
                            </div>
                            
                            <CardHeader>
                                <div className="flex justify-between items-start">
                                    <CardTitle className="text-xl font-bold text-gray-800 dark:text-white line-clamp-2">
                                        {service.nombre}
                                    </CardTitle>
                                    <div className="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-sm font-bold px-2 py-1 rounded">
                                        S/ {parseFloat(service.precio).toFixed(2)}
                                    </div>
                                </div>
                            </CardHeader>

                            <CardContent className="flex-grow">
                                <p className="text-gray-600 dark:text-gray-300 text-sm line-clamp-3">
                                    {service.descripcion || 'Sin descripción disponible.'}
                                </p>
                            </CardContent>

                            <CardFooter className="pt-0 bg-transparent border-t-0">
                                <Button 
                                    className="w-full"
                                    onClick={() => handleReserva(service.tipo_id)}
                                >
                                    <ShoppingCart className="mr-2 h-4 w-4" />
                                    Reservar Cita
                                </Button>
                            </CardFooter>
                        </Card>
                    ))}
                </div>
            )}
        </div>
    );
}
