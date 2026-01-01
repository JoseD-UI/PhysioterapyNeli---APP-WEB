<?php

namespace App\Repositories\Principal;

use App\Models\Principal\Persona;
use App\Models\Principal\Usuario;
use App\Models\Principal\Rol;
use App\Models\Principal\Sala;
use App\Models\Principal\Permiso;

class PrincipalRepository
{
    /* ============================
       PERSONAS
    ============================= */

    public function listarPersonas()
    {
        return Persona::orderBy('creado_en', 'desc')->get();
    }

    public function crearPersona(array $data)
    {
        return Persona::create($data);
    }

    public function obtenerPersona($id)
    {
        return Persona::find($id);
    }

    public function actualizarPersona($id, array $data)
    {
        $persona = Persona::find($id);
        if (!$persona) return null;

        $persona->update($data);
        return $persona;
    }

    public function eliminarPersona($id)
    {
        $persona = Persona::find($id);
        return $persona?->delete();
    }

    /* ============================
       USUARIOS
    ============================= */

    public function listarUsuarios()
    {
        return Usuario::with('rol', 'persona')->orderBy('creado_en', 'desc')->get();
    }

    public function crearUsuario(array $data)
    {
        return Usuario::create($data);
    }

    public function obtenerUsuario($id)
    {
        return Usuario::find($id);
    }

    public function actualizarUsuario($id, array $data)
    {
        $user = Usuario::find($id);
        if (!$user) return null;

        $user->update($data);
        return $user;
    }

    public function eliminarUsuario($id)
    {
        $user = Usuario::find($id);
        return $user?->delete();
    }

    /* ============================
       ROLES
    ============================= */

    public function listarRoles()
    {
        return Rol::orderBy('creado_en', 'desc')->get();
    }

    public function crearRol(array $data)
    {
        return Rol::create($data);
    }

    public function obtenerRol($id)
    {
        return Rol::find($id);
    }

    public function actualizarRol($id, array $data)
    {
        $rol = Rol::find($id);
        if (!$rol) return null;

        $rol->update($data);
        return $rol;
    }

    public function eliminarRol($id)
    {
        $rol = Rol::find($id);
        return $rol?->delete();
    }

    // SALAS
    public function listarSalas() { return Sala::orderBy('nombre_sala')->get(); }
    public function crearSala(array $data) { return Sala::create($data); }
    public function obtenerSala($id) { return Sala::find($id); }
    public function actualizarSala($id, array $data) { $s = Sala::find($id); if(!$s) return null; $s->update($data); return $s; }
    public function eliminarSala($id) { $s = Sala::find($id); return $s ? $s->delete() : false; }

    // PERMISOS
    public function listarPermisos() { return Permiso::orderBy('nombre')->get(); }
    public function crearPermiso(array $data) { return Permiso::create($data); }
    public function obtenerPermiso($id) { return Permiso::find($id); }
    public function actualizarPermiso($id, array $data) { $p = Permiso::find($id); if(!$p) return null; $p->update($data); return $p; }
    public function eliminarPermiso($id) { $p = Permiso::find($id); return $p ? $p->delete() : false; }

}
