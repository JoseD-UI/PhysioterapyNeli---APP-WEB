<?php

namespace Tests\Helpers;

use App\Models\User;
use App\Models\Principal\Persona;
use App\Models\Principal\Usuario;
use App\Models\Principal\Rol;

trait CreatesTestUsers
{
    protected function createAdminUser(): User
    {
        $user = User::factory()->create();
        
        $persona = Persona::create([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'EMPLEADO',
            'dni' => '87654321' . rand(10, 99),
            'nombres' => 'Admin',
            'apellidos' => 'Test',
        ]);
        
        $rolAdmin = Rol::where('nombre', 'ADMINISTRADOR')->first();
        
        Usuario::create([
            'usuario_id' => $user->id,
            'persona_id' => $persona->persona_id,
            'rol_id' => $rolAdmin->rol_id,
            'username' => 'admin_' . time() . rand(100, 999),
            'password_hash' => bcrypt('password'),
            'activo' => 1
        ]);
        
        $user->refresh();
        $user->load('usuarioPrincipal.rol.permisos');
        
        return $user;
    }

    protected function createFisioterapeutaUser(): User
    {
        $user = User::factory()->create();
        
        $persona = Persona::create([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'FISIOTERAPEUTA',
            'dni' => '11223344' . rand(10, 99),
            'nombres' => 'Fisio',
            'apellidos' => 'Tester',
        ]);
        
        $rolFisio = Rol::where('nombre', 'FISIOTERAPEUTA')->first();
        
        Usuario::create([
            'usuario_id' => $user->id,
            'persona_id' => $persona->persona_id,
            'rol_id' => $rolFisio->rol_id,
            'username' => 'fisio_' . time() . rand(100, 999),
            'password_hash' => bcrypt('password'),
            'activo' => 1
        ]);
        
        $user->refresh();
        $user->load('usuarioPrincipal.rol.permisos');
        
        return $user;
    }

    protected function createRecepcionistaUser(): User
    {
        $user = User::factory()->create();
        
        $persona = Persona::create([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'EMPLEADO',
            'dni' => '55667788' . rand(10, 99),
            'nombres' => 'Recepcion',
            'apellidos' => 'Tester',
        ]);
        
        $rolRecep = Rol::where('nombre', 'RECEPCIONISTA')->first();
        
        Usuario::create([
            'usuario_id' => $user->id,
            'persona_id' => $persona->persona_id,
            'rol_id' => $rolRecep->rol_id,
            'username' => 'recep_' . time() . rand(100, 999),
            'password_hash' => bcrypt('password'),
            'activo' => 1
        ]);
        
        $user->refresh();
        $user->load('usuarioPrincipal.rol.permisos');
        
        return $user;
    }
}
