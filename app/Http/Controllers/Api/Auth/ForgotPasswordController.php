<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Enviar enlace de restablecimiento de contraseña
     * POST /api/v1/auth/forgot-password
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Enviaremos el enlace usando el Facade Password nativo de Laravel.
        // Esto usa la configuración en config/auth.php y config/mail.php
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => __($status), 
                'message' => 'Enlace de recuperación enviado'
            ]);
        }

        // Si falla, lanzamos error de validación para seguridad (o genérico)
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
