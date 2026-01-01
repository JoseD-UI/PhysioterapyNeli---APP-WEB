<?php

namespace App\Services\Principal;

use App\Repositories\Principal\PrincipalRepository;
use Illuminate\Support\Facades\DB;

class PrincipalService
{
    protected $repo;

    public function __construct(PrincipalRepository $repo)
    {
        $this->repo = $repo;
    }

    /* PERSONAS */

    public function listarPersonas()
    {
        return $this->repo->listarPersonas();
    }

    public function crearPersona(array $data)
    {
        return DB::transaction(fn() => $this->repo->crearPersona($data));
    }

    public function obtenerPersona($id)
    {
        return $this->repo->obtenerPersona($id);
    }

    public function actualizarPersona($id, array $data)
    {
        return DB::transaction(fn() => $this->repo->actualizarPersona($id, $data));
    }

    public function eliminarPersona($id)
    {
        return $this->repo->eliminarPersona($id);
    }

    /* USUARIOS */

    public function listarUsuarios()
    {
        return $this->repo->listarUsuarios();
    }

    public function crearUsuario(array $data)
    {
        return DB::transaction(fn() => $this->repo->crearUsuario($data));
    }

    public function obtenerUsuario($id)
    {
        return $this->repo->obtenerUsuario($id);
    }

    public function actualizarUsuario($id, array $data)
    {
        return DB::transaction(fn() => $this->repo->actualizarUsuario($id, $data));
    }

    public function eliminarUsuario($id)
    {
        return $this->repo->eliminarUsuario($id);
    }

    /* ROLES */

    public function listarRoles()
    {
        return $this->repo->listarRoles();
    }

    public function crearRol(array $data)
    {
        return $this->repo->crearRol($data);
    }

    public function obtenerRol($id)
    {
        return $this->repo->obtenerRol($id);
    }

    public function actualizarRol($id, array $data)
    {
        return $this->repo->actualizarRol($id, $data);
    }

    public function eliminarRol($id)
    {
        return $this->repo->eliminarRol($id);
    }

    // Salas
    public function listarSalas(){ return $this->repo->listarSalas(); }
    public function crearSala(array $data){ return DB::transaction(fn()=> $this->repo->crearSala($data)); }
    public function obtenerSala($id){ return $this->repo->obtenerSala($id); }
    public function actualizarSala($id,array $data){ return DB::transaction(fn()=> $this->repo->actualizarSala($id,$data)); }
    public function eliminarSala($id){ return $this->repo->eliminarSala($id); }

    // Permisos
    public function listarPermisos(){ return $this->repo->listarPermisos(); }
    public function crearPermiso(array $data){ return DB::transaction(fn()=> $this->repo->crearPermiso($data)); }
    public function obtenerPermiso($id){ return $this->repo->obtenerPermiso($id); }
    public function actualizarPermiso($id,array $data){ return DB::transaction(fn()=> $this->repo->actualizarPermiso($id,$data)); }
    public function eliminarPermiso($id){ return $this->repo->eliminarPermiso($id); }

}
