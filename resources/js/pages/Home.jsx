import React from 'react';
import { motion } from 'framer-motion';
import { Button } from '../components/ui/Button';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/Card';
import { ArrowRight, Activity, Heart, Zap, Play } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';

export default function Home() {
    const { isAuthenticated } = useAuth();
    return (
        <div className="w-full overflow-hidden">
            {/* HER0 SECTION */}
            <section className="relative h-screen min-h-[600px] flex items-center justify-center">
                {/* Background Image with Overlay */}
                <div className="absolute inset-0 z-0">
                    <img 
                        src="/images/hero-bg.png" 
                        alt="Background" 
                        className="w-full h-full object-cover"
                    />
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-cyan-900/40 backdrop-blur-[2px]"></div>
                </div>

                {/* Content */}
                <div className="relative z-10 container mx-auto px-6 text-center md:text-left">
                    <motion.div 
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8 }}
                        className="max-w-3xl"
                    >
                        <div className="inline-block px-4 py-1 mb-4 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-sm font-semibold backdrop-blur-md">
                            👋 Bienvenido a PhysioApp
                        </div>
                        <h1 className="text-5xl md:text-7xl font-extrabold text-white tracking-tight leading-tight mb-6">
                            Recupera tu <span className="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Movimiento</span>,<br />
                            Potencia tu Vida.
                        </h1>
                        <p className="text-lg md:text-xl text-blue-100 mb-8 max-w-2xl font-light">
                            Tecnología avanzada y especialistas dedicados para tu rehabilitación física y deportiva. Tu salud en las mejores manos.
                        </p>
                        
                        <div className="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            {isAuthenticated ? (
                                <Link to="/admin">
                                    <Button size="lg" className="h-14 px-8 text-lg rounded-full shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all">
                                        Ir a mi Panel
                                        <ArrowRight className="ml-2 h-5 w-5" />
                                    </Button>
                                </Link>
                            ) : (
                                <Link to="/register">
                                    <Button size="lg" className="h-14 px-8 text-lg rounded-full shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all">
                                        Reserva tu Cita
                                        <ArrowRight className="ml-2 h-5 w-5" />
                                    </Button>
                                </Link>
                            )}
                            
                            <Link to="/servicios">
                                <Button variant="outline" size="lg" className="h-14 px-8 text-lg rounded-full border-white/30 text-white hover:bg-white/10 backdrop-blur-sm">
                                    <Play className="mr-2 h-5 w-5 fill-current" />
                                    Conoce más
                                </Button>
                            </Link>
                        </div>
                    </motion.div>
                </div>

                {/* Floating Cards (Decoration) */}
                <motion.div 
                    initial={{ opacity: 0, x: 50 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 0.5, duration: 1 }}
                    className="absolute bottom-20 right-10 hidden lg:block"
                >
                    <div className="p-6 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl w-80 text-white">
                        <div className="flex items-center gap-4 mb-4">
                            <div className="p-3 bg-green-500/20 rounded-full text-green-400">
                                <Activity className="h-6 w-6" />
                            </div>
                            <div>
                                <p className="font-bold text-lg">98% Éxito</p>
                                <p className="text-sm text-blue-200">En recuperación</p>
                            </div>
                        </div>
                        <div className="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                            <div className="h-full w-[98%] bg-gradient-to-r from-green-400 to-emerald-500"></div>
                        </div>
                    </div>
                </motion.div>
            </section>

            {/* SERVICES PREVIEW */}
            <section className="py-24 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
                <div className="container mx-auto px-6">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Nuestros Servicios</h2>
                        <p className="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            Ofrecemos un enfoque integral para tu bienestar, combinando terapia manual, tecnología y ejercicio terapéutico.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* Service 1 */}
                        <Card className="hover:shadow-xl transition-shadow border-none shadow-md">
                            <CardHeader>
                                <div className="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4 text-blue-600 dark:text-blue-400">
                                    <Zap className="h-6 w-6" />
                                </div>
                                <CardTitle>Fisioterapia Deportiva</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-600 dark:text-gray-300 mb-4">
                                    Recuperación acelerada de lesiones y optimización del rendimiento para atletas de todos los niveles.
                                </p>
                                <Button variant="link" className="p-0 text-blue-600 dark:text-blue-400">Leer más →</Button>
                            </CardContent>
                        </Card>

                        {/* Service 2 */}
                        <Card className="hover:shadow-xl transition-shadow border-none shadow-md">
                            <CardHeader>
                                <div className="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4 text-purple-600 dark:text-purple-400">
                                    <Activity className="h-6 w-6" />
                                </div>
                                <CardTitle>Rehabilitación Física</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-600 dark:text-gray-300 mb-4">
                                    Tratamientos personalizados para dolor de espalda, post-operatorios y problemas musculoesqueléticos.
                                </p>
                                <Button variant="link" className="p-0 text-purple-600 dark:text-purple-400">Leer más →</Button>
                            </CardContent>
                        </Card>

                        {/* Service 3 */}
                        <Card className="hover:shadow-xl transition-shadow border-none shadow-md">
                            <CardHeader>
                                <div className="w-12 h-12 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mb-4 text-pink-600 dark:text-pink-400">
                                    <Heart className="h-6 w-6" />
                                </div>
                                <CardTitle>Bienestar Integral</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-gray-600 dark:text-gray-300 mb-4">
                                    Masajes terapéuticos, punción seca y técnicas avanzadas para mantener tu cuerpo en equilibrio.
                                </p>
                                <Button variant="link" className="p-0 text-pink-600 dark:text-pink-400">Leer más →</Button>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </section>
        </div>
    );
}
