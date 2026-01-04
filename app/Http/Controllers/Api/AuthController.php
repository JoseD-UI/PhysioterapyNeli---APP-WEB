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
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * REGISTRO DE NUEVO USUARIO
     * 
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        // 1. Validar Tipo de Persona primero
        $request->validate([
            'tipo_persona' => 'required|in:' . Persona::TIPO_PACIENTE_NATURAL . ',' . Persona::TIPO_PACIENTE_JURIDICA,
        ]);

        $tipoPersona = $request->tipo_persona;

        // 2. Definir reglas base
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:dns|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
        ];

        // 3. Reglas específicas por Tipo de Persona
        if ($tipoPersona === Persona::TIPO_PACIENTE_NATURAL) {
            $rules['documento_tipo'] = 'required|in:DNI,CARNET_EXT';
            // Validación condicional del número según tipo
            if ($request->documento_tipo === 'DNI') {
                $rules['documento_numero'] = 'required|digits:8|unique:principal_personas,dni';
            } else {
                $rules['documento_numero'] = 'required|digits:9|unique:principal_personas,dni'; // Carnet Extranjería (asumiendo 9 numéricos)
            }
            $rules['nombres'] = 'required|string|max:150';
            $rules['apellidos'] = 'required|string|max:150';
            $rules['fecha_nacimiento'] = 'required|date|before:' . now()->subYears(18)->format('Y-m-d');
        
        } else {
            // PACIENTE-JURIDICA
            $rules['documento_tipo'] = 'required|in:RUC';
            $rules['documento_numero'] = 'required|digits:11|unique:principal_personas,ruc'; // RUC 11 dígitos
            $rules['nombres'] = 'required|string|max:150'; // Razón Social
            // Apellidos se autocompleta con "P.J."
            // Fecha nacimiento no requerida
        }

        $validator = Validator::make($request->all(), $rules, [
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, un número y un símbolo.',
            'fecha_nacimiento.before' => 'Debes ser mayor de 18 años para registrarte.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'documento_numero.unique' => 'El documento ya está registrado.',
            'documento_numero.digits' => 'El documento debe tener la longitud correcta.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error de validación', 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Crear usuario auth
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Preparar datos persona
            $personaData = [
                'tipo_persona' => $tipoPersona,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'creado_en' => now(),
            ];

            if ($tipoPersona === Persona::TIPO_PACIENTE_NATURAL) {
                $personaData['nombres'] = $request->nombres;
                $personaData['apellidos'] = $request->apellidos;
                $personaData['dni'] = $request->documento_numero; // Guardamos en columna DNI
                $personaData['fecha_nacimiento'] = $request->fecha_nacimiento;
            } else {
                $personaData['nombres'] = $request->nombres; // Razón Social
                $personaData['apellidos'] = 'P.J.';
                $personaData['ruc'] = $request->documento_numero; // Guardamos en columna RUC
                $personaData['fecha_nacimiento'] = null;
            }

            $persona = Persona::create($personaData);

            // 3. Crear usuario principal
            Usuario::create([
                'user_id' => $user->id,
                'persona_id' => $persona->persona_id,
                'username' => \Illuminate\Support\Str::slug($user->name) . '-' . rand(1000, 9999),
                'rol_id' => 2, // Rol PACIENTE (Asegurar ID correcto en BD)
                'activo' => 1,
            ]);

            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registro exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_complete' => true // Al registrarse manualmente, siempre es true por validación
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error interno', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error de validación', 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => ['Las credenciales son incorrectas.']]);
        }

        $usuarioPrincipal = $user->usuarioPrincipal;
        if ($usuarioPrincipal && (int)$usuarioPrincipal->activo !== 1) {
            return response()->json(['message' => 'Usuario inactivo.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Verificar completitud de perfil
        $profileComplete = $usuarioPrincipal && $usuarioPrincipal->persona ? $usuarioPrincipal->persona->isProfileComplete() : false;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_complete' => $profileComplete,
                'usuario_principal' => $usuarioPrincipal ? [
                    'rol' => $usuarioPrincipal->rol->nombre ?? null,
                    'persona_id' => $usuarioPrincipal->persona_id,
                ] : null,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $usuarioPrincipal = $user->usuarioPrincipal()->with('persona', 'rol')->first();

        $profileComplete = $usuarioPrincipal && $usuarioPrincipal->persona ? $usuarioPrincipal->persona->isProfileComplete() : false;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_complete' => $profileComplete,
                // Datos crudos para el modal de completado
                'persona_raw' => $usuarioPrincipal ? $usuarioPrincipal->persona : null,
                'usuario_principal' => $usuarioPrincipal ? [
                    'id' => $usuarioPrincipal->id,
                    'active' => $usuarioPrincipal->activo,
                    'rol' => $usuarioPrincipal->rol->nombre ?? null,
                    'persona' => [
                        'nombres' => $usuarioPrincipal->persona->nombres ?? null,
                        'apellidos' => $usuarioPrincipal->persona->apellidos ?? null,
                        'tipo_persona' => $usuarioPrincipal->persona->tipo_persona ?? null,
                        'dni' => $usuarioPrincipal->persona->dni ?? null,
                        'ruc' => $usuarioPrincipal->persona->ruc ?? null,
                        'telefono' => $usuarioPrincipal->persona->telefono ?? null,
                        'direccion' => $usuarioPrincipal->persona->direccion ?? null,
                    ],
                    'permisos' => $usuarioPrincipal->rol->permisosCodigos() ?? [],
                ] : null,
            ]
        ]);
    }

    /**
     * Completar perfil (para usuarios de Google o incompletos)
     */
    public function completeProfile(Request $request)
    {
        $user = $request->user();
        $usuarioPrincipal = $user->usuarioPrincipal;

        if (!$usuarioPrincipal || !$usuarioPrincipal->persona) {
            return response()->json(['message' => 'No se encontró perfil asociado.'], 404);
        }

        $persona = $usuarioPrincipal->persona;

        // Validar campos faltantes. Usamos lógica similar al registro.
        // Permitimos enviar tipo_persona si no tiene
        $request->validate([
            'tipo_persona' => 'required|in:' . Persona::TIPO_PACIENTE_NATURAL . ',' . Persona::TIPO_PACIENTE_JURIDICA,
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
        ]);
        
        $tipoPersona = $request->tipo_persona;
        
        // Validaciones dinámicas (igual que register)
        $rules = [];
        if ($tipoPersona === Persona::TIPO_PACIENTE_NATURAL) {
            $rules['documento_tipo'] = 'required|in:DNI,CARNET_EXT';
            if ($request->documento_tipo === 'DNI') {
                $rules['documento_numero'] = 'required|digits:8|unique:principal_personas,dni,' . $persona->persona_id . ',persona_id';
            } else {
                $rules['documento_numero'] = 'required|digits:9|unique:principal_personas,dni,' . $persona->persona_id . ',persona_id';
            }
            $rules['fecha_nacimiento'] = 'required|date|before:' . now()->subYears(18)->format('Y-m-d');
        } else {
             $rules['documento_tipo'] = 'required|in:RUC';
             $rules['documento_numero'] = 'required|digits:11|unique:principal_personas,ruc,' . $persona->persona_id . ',persona_id';
        }
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar
        try {
            $persona->tipo_persona = $tipoPersona;
            $persona->telefono = $request->telefono;
            $persona->direccion = $request->direccion;

            if ($tipoPersona === Persona::TIPO_PACIENTE_NATURAL) {
                $persona->dni = $request->documento_numero;
                $persona->fecha_nacimiento = $request->fecha_nacimiento;
                // Si faltan nombres/apellidos por ser Google, los actualizamos si vienen
                if ($request->nombres) $persona->nombres = $request->nombres;
                if ($request->apellidos) $persona->apellidos = $request->apellidos;
            } else {
                $persona->ruc = $request->documento_numero;
                if ($request->nombres) $persona->nombres = $request->nombres; // Razón Social
                $persona->apellidos = 'P.J.';
            }

            $persona->save();

            return response()->json(['message' => 'Perfil completado exitosamente', 'user' => $this->me($request)->original['user']]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al guardar perfil', 'error' => $e->getMessage()], 500);
        }
    }
}
