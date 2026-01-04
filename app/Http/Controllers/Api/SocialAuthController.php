<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Principal\Usuario;
use App\Models\Principal\Persona;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SocialAuthController extends Controller
{
    /**
     * Maneja el login/registro con Google
     * POST /api/v1/auth/google
     */
    public function googleLogin(Request $request)
    {
        // Validar que venga el token de Google desde el frontend
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            // Obtener usuario de Google usando el token (Stateless)
            $socialUser = Socialite::driver('google')->stateless()->userFromToken($request->access_token);
            
            // Buscar usuario existente
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // REGISTRO DE NUEVO USUARIO
                DB::transaction(function () use ($socialUser, &$user, $request) {
                    // 1. Crear User
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'google_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                        'password' => null, // Sin password
                    ]);

                    // 2. Crear Persona
                    // Intentamos separar nombre y apellido basicamene
                    $parts = explode(' ', $socialUser->getName(), 2);
                    $nombre = $parts[0] ?? $socialUser->getName();
                    $apellido = $parts[1] ?? '';

                    // Validar edad si se envía
                    if ($request->filled('fecha_nacimiento')) {
                        $fechaNacimiento = \Carbon\Carbon::parse($request->fecha_nacimiento);
                        if ($fechaNacimiento->age < 18) {
                            throw new \Exception('Debes ser mayor de 18 años para registrarte.');
                        }
                    }

                    $persona = Persona::create([
                        'tipo_persona' => 'PACIENTE',
                        'nombres' => $nombre,
                        'apellidos' => $apellido,
                        'email' => $socialUser->getEmail(),
                        'dni' => $request->dni,
                        'telefono' => $request->telefono,
                        'direccion' => $request->direccion,
                        'fecha_nacimiento' => $request->fecha_nacimiento,
                    ]);

                    // 3. Crear Usuario Principal (Rol Paciente por defecto)
                    Usuario::create([
                        'user_id' => $user->id,
                        'persona_id' => $persona->persona_id,
                        'username' => \Illuminate\Support\Str::slug($nombre) . '-' . rand(1000, 9999),
                        'rol_id' => 2, // ID 2 = PACIENTE (Según README/Seeds)
                        'activo' => 1,
                    ]);
                });
            } else {
                // Actualizar google_id y avatar si ya existía por email normal
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }

            // Verificar activo
            if ($user->usuarioPrincipal && !$user->usuarioPrincipal->activo) {
                return response()->json(['message' => 'Usuario inactivo'], 403);
            }

            // Generar Token Sanctum
            $token = $user->createToken('google_auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'usuario_principal' => $user->usuarioPrincipal ? [
                        'rol' => $user->usuarioPrincipal->rol->nombre ?? null,
                    ] : null,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error autenticando con Google',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
