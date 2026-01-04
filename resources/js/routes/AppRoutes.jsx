import React from 'react';
import { Routes, Route } from 'react-router-dom';
import PublicLayout from '../layouts/PublicLayout';
import MainLayout from '../layouts/MainLayout';
import Home from '../pages/Home';
import Services from '../pages/Services';
import Login from '../pages/auth/Login';
import Register from '../pages/auth/Register';
import ForgotPassword from '../pages/auth/ForgotPassword';
import ResetPassword from '../pages/auth/ResetPassword';

// Placeholder para componentes futuros
const Dashboard = () => <div className="p-10 text-center"><h1>Admin Dashboard (Próximamente)</h1></div>;

export default function AppRoutes() {
    return (
        <Routes>
            {/* Rutas Públicas */}
            <Route path="/" element={<PublicLayout />}>
                <Route index element={<Home />} />
                <Route path="servicios" element={<Services />} />
                <Route path="login" element={<Login />} />
                <Route path="register" element={<Register />} />
                <Route path="forgot-password" element={<ForgotPassword />} />
                <Route path="password-reset" element={<ResetPassword />} />
            </Route>

            {/* Rutas Privadas (Placeholder) */}
            <Route path="/admin" element={<MainLayout />}>
                <Route index element={<Dashboard />} />
            </Route>
            
            {/* 404 */}
            <Route path="*" element={<div className="p-20 text-center text-red-500">404 - Ruta no encontrada</div>} />
        </Routes>
    );
}
