import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import PublicLayout from '../layouts/PublicLayout';
import MainLayout from '../layouts/MainLayout';
import ProtectedRoute from '../components/layout/ProtectedRoute';
import Home from '../pages/Home';
import Services from '../pages/Services';
import Login from '../pages/auth/Login';
import Register from '../pages/auth/Register';
import ForgotPassword from '../pages/auth/ForgotPassword';
import ResetPassword from '../pages/auth/ResetPassword';
import AgendaPage from '../features/Agenda';
import ClinicoPage from '../features/Clinico';
import UserClinicView from '../features/Clinico/UserClinicView';
import AdminClinicView from '../features/Clinico/AdminClinicView';

// Placeholder para componentes futuros
const Dashboard = () => <div className="p-10 text-center"><h1>Admin Dashboard - Bienvenido</h1></div>;

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

            {/* Rutas Privadas */}
            <Route path="/admin" element={<ProtectedRoute />}>
                <Route element={<MainLayout />}>
                    <Route index element={<Dashboard />} />
                    <Route path="agenda" element={<AgendaPage />} />
                    <Route path="clinico" element={<ClinicoPage />} />
                    <Route path="clinico-usuario" element={<UserClinicView />} />
                    <Route path="clinico-admin" element={<AdminClinicView />} />
                </Route>
            </Route>
            
            {/* 404 */}
            <Route path="*" element={<div className="p-20 text-center text-red-500">404 - Ruta no encontrada</div>} />
        </Routes>
    );
}
