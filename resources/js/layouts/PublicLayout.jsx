import React from 'react';
import Navbar from '../components/layout/Navbar';
import { Outlet } from 'react-router-dom';

export default function PublicLayout() {
    return (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-900 font-sans transition-colors duration-200">
            <Navbar />
            
            <main>
                <Outlet />
            </main>

            <footer className="bg-gray-900 dark:bg-black text-white py-12 mt-20 border-t border-gray-800">
                <div className="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 className="text-xl font-bold mb-4">PhysioApp</h3>
                        <p className="text-gray-400 text-sm">Tu salud en las mejores manos. Especialistas en rehabilitación física y deportiva.</p>
                    </div>
                    <div>
                        <h4 className="font-semibold mb-4">Enlaces</h4>
                        <ul className="space-y-2 text-sm text-gray-400">
                            <li>Inicio</li>
                            <li>Servicios</li>
                            <li>Reserva</li>
                        </ul>
                    </div>
                    <div>
                        <h4 className="font-semibold mb-4">Contacto</h4>
                        <ul className="space-y-2 text-sm text-gray-400">
                            <li>Av. Principal 123</li>
                            <li>contacto@physio.com</li>
                            <li>+51 999 888 777</li>
                        </ul>
                    </div>
                    <div>
                        <h4 className="font-semibold mb-4">Horario</h4>
                        <p className="text-sm text-gray-400">Lun - Vie: 8am - 8pm</p>
                        <p className="text-sm text-gray-400">Sab: 9am - 1pm</p>
                    </div>
                </div>
                <div className="border-t border-gray-800 mt-8 pt-8 text-center text-xs text-gray-500">
                    © 2026 PhysioApp. Todos los derechos reservados.
                </div>
            </footer>
        </div>
    );
}
