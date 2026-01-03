<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Principal\Usuario;
use App\Models\Principal\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * REGISTRO DE NUEVO USUARIO
     * 
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            
            // Datos de persona (opcional en registro básico)
            'tipo_persona' => 'nullable|in:PACIENTE,FISIOTERAPEUTA,ADMINISTRATIVO',
            'nombres' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:20|unique:principal_personas,dni',
            'telefono' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear usuario de autenticación
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Crear persona si se proporcionaron datos
        if ($request->filled('nombres') || $request->filled('apellidos')) {
            $persona = Persona::create([
                'tipo_persona' => $request->tipo_persona ?? 'PACIENTE',
                'nombres' => $request->nombres ?? $request->name,
                'apellidos' => $request->apellidos ?? '',
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ]);

            // Crear usuario principal vinculado
            Usuario::create([
                'usuario_id' => $user->id,
                'persona_id' => $persona->persona_id,
                'rol_id' => 2, // Rol por defecto (ej: PACIENTE)
                'activo' => 1,
            ]);
        }

        // Generar token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * LOGIN
     * 
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        // Verificar si el usuario está activo
        $usuarioPrincipal = $user->usuarioPrincipal;
        if ($usuarioPrincipal && (int) $usuarioPrincipal->activo !== 1) {
            return response()->json([
                'message' => 'Usuario inactivo. Contacte al administrador.'
            ], 403);
        }

        // Revocar tokens anteriores (opcional)
        // $user->tokens()->delete();

        // Generar nuevo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'usuario_principal' => $usuarioPrincipal ? [
                    'rol' => $usuarioPrincipal->rol->nombre ?? null,
                    'persona' => [
                        'nombres' => $usuarioPrincipal->persona->nombres ?? null,
                        'apellidos' => $usuarioPrincipal->persona->apellidos ?? null,
                    ]
                ] : null,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * LOGOUT
     * 
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        // Revocar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * INFORMACIÓN DEL USUARIO AUTENTICADO
     * 
     * GET /api/v1/auth/me
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $usuarioPrincipal = $user->usuarioPrincipal;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'usuario_principal' => $usuarioPrincipal ? [
                    'usuario_id' => $usuarioPrincipal->usuario_id,
                    'activo' => $usuarioPrincipal->activo,
                    'rol' => [
                        'id' => $usuarioPrincipal->rol->rol_id ?? null,
                        'nombre' => $usuarioPrincipal->rol->nombre ?? null,
                        'descripcion' => $usuarioPrincipal->rol->descripcion ?? null,
                    ],
                    'persona' => [
                        'persona_id' => $usuarioPrincipal->persona->persona_id ?? null,
                        'tipo_persona' => $usuarioPrincipal->persona->tipo_persona ?? null,
                        'nombres' => $usuarioPrincipal->persona->nombres ?? null,
                        'apellidos' => $usuarioPrincipal->persona->apellidos ?? null,
                        'dni' => $usuarioPrincipal->persona->dni ?? null,
                        'telefono' => $usuarioPrincipal->persona->telefono ?? null,
                    ],
                    'permisos' => $usuarioPrincipal->rol->permisosCodigos() ?? [],
                ] : null,
            ]
        ]);
    }
}
