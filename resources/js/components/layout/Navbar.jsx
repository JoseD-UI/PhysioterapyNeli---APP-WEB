import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { Button } from '../ui/Button';
import { Menu, X, User, LogOut, Calendar } from 'lucide-react';
import { cn } from '../../lib/utils';

export default function Navbar() {
    const { user, isAuthenticated, logout } = useAuth();
    const navigate = useNavigate();
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    const handleLogout = async () => {
        await logout();
        navigate('/');
    };

    return (
        <nav className="sticky top-0 z-50 w-full border-b border-gray-100 dark:border-gray-800 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md transition-colors duration-300">
            <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                {/* Logo */}
                <div className="flex items-center gap-2">
                    <Link to="/" className="flex items-center gap-2">
                        <div className="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/30">
                            F
                        </div>
                        <span className="text-xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">
                            PhysioApp
                        </span>
                    </Link>
                </div>

                {/* Desktop Navigation */}
                <div className="hidden md:flex items-center gap-8">
                    <Link to="/" className="text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Inicio
                    </Link>
                    <Link to="/servicios" className="text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Servicios
                    </Link>
                    <Link to="/nosotros" className="text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Nosotros
                    </Link>
                </div>

                {/* Auth Actions (Desktop) */}
                <div className="hidden md:flex items-center gap-4">
                    
                    {isAuthenticated ? (
                        <div className="flex items-center gap-4">
                            <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Hola, {user?.name}
                            </span>
                            <div className="h-8 w-px bg-gray-200 dark:bg-gray-700"></div>
                            
                            {user?.usuario_principal?.rol?.nombre === 'ADMINISTRADOR' && (
                                <Link to="/admin">
                                    <Button variant="ghost" size="sm">Dashboard</Button>
                                </Link>
                            )}

                            <Link to="/client/citas">
                                <Button size="sm" variant="primary">
                                    <Calendar className="mr-2 h-4 w-4" />
                                    Mis Citas
                                </Button>
                            </Link>

                            <Button 
                                variant="ghost" 
                                size="sm" 
                                onClick={handleLogout}
                                className="text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                            >
                                <LogOut className="h-4 w-4" />
                            </Button>
                        </div>
                    ) : (
                        <div className="flex items-center gap-2">
                            <Link to="/login">
                                <Button variant="ghost" size="sm" className="dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-800">Iniciar Sesión</Button>
                            </Link>
                            <Link to="/register">
                                <Button size="sm">Registrarse</Button>
                            </Link>
                        </div>
                    )}
                </div>

                {/* Mobile Menu Button */}
                <div className="flex items-center gap-4 md:hidden">
                    <button 
                        className="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
                        onClick={() => setIsMenuOpen(!isMenuOpen)}
                    >
                        {isMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
                    </button>
                </div>
            </div>

            {/* Mobile Menu */}
            {isMenuOpen && (
                <div className="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-4 space-y-4 shadow-lg animate-in slide-in-from-top-4">
                    <div className="flex flex-col gap-2">
                        <Link to="/" className="block py-2 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg px-2">Inicio</Link>
                        <Link to="/servicios" className="block py-2 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg px-2">Servicios</Link>
                        {!isAuthenticated && (
                             <Link to="/login" className="block py-2 text-base font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg px-2">Iniciar Sesión</Link>
                        )}
                         {isAuthenticated && (
                            <button onClick={handleLogout} className="block w-full text-left py-2 text-base font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg px-2">
                                Cerrar Sesión
                            </button>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
