<?php

namespace App\Http\Controllers\Api\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Principal\PrincipalService;

use App\Http\Requests\Principal\PersonaRequest;
use App\Http\Requests\Principal\UsuarioRequest;
use App\Http\Requests\Principal\RolRequest;
use App\Http\Requests\Principal\SalaRequest;
use App\Http\Requests\Principal\PermisoRequest;

use App\Http\Resources\Principal\PersonaResource;
use App\Http\Resources\Principal\UsuarioResource;
use App\Http\Resources\Principal\RolResource;
use App\Http\Resources\Principal\SalaResource;
use App\Http\Resources\Principal\PermisoResource;

class PrincipalController extends Controller
{
    protected $service;

    public function __construct(PrincipalService $service)
    {
        $this->service = $service;
    }

    /* ============================
       PERSONAS
    ============================= */

    public function personasIndex()
    {
        return PersonaResource::collection($this->service->listarPersonas());
    }

    public function personasStore(PersonaRequest $request)
    {
        return new PersonaResource(
            $this->service->crearPersona($request->validated())
        );
    }

    public function personasShow($id)
    {
        return new PersonaResource($this->service->obtenerPersona($id));
    }

    public function personasUpdate(PersonaRequest $request, $id)
    {
        return new PersonaResource(
            $this->service->actualizarPersona($id, $request->validated())
        );
    }

    public function personasDelete($id)
    {
        $this->service->eliminarPersona($id);
        return response()->json(['message' => 'Persona eliminada']);
    }

    /* ============================
       USUARIOS
    ============================= */

    public function usuariosIndex()
    {
        return UsuarioResource::collection($this->service->listarUsuarios());
    }

    public function usuariosStore(UsuarioRequest $request)
    {
        return new UsuarioResource(
            $this->service->crearUsuario($request->validated())
        );
    }

    public function usuariosShow($id)
    {
        return new UsuarioResource($this->service->obtenerUsuario($id));
    }

    public function usuariosUpdate(UsuarioRequest $request, $id)
    {
        return new UsuarioResource(
            $this->service->actualizarUsuario($id, $request->validated())
        );
    }

    public function usuariosDelete($id)
    {
        $this->service->eliminarUsuario($id);
        return response()->json(['message' => 'Usuario eliminado']);
    }

    /* ============================
       ROLES
    ============================= */

    public function rolesIndex()
    {
        return RolResource::collection($this->service->listarRoles());
    }

    public function rolesStore(RolRequest $request)
    {
        return new RolResource(
            $this->service->crearRol($request->validated())
        );
    }

    public function rolesShow($id)
    {
        return new RolResource($this->service->obtenerRol($id));
    }

    public function rolesUpdate(RolRequest $request, $id)
    {
        return new RolResource(
            $this->service->actualizarRol($id, $request->validated())
        );
    }

    public function rolesDelete($id)
    {
        $this->service->eliminarRol($id);
        return response()->json(['message' => 'Rol eliminado']);
    }

    // ---------- Salas ----------
    public function salasIndex(){ return SalaResource::collection($this->service->listarSalas()); }
    public function salasStore(SalaRequest $req){ return new SalaResource($this->service->crearSala($req->validated())); }
    public function salasShow($id){ return new SalaResource($this->service->obtenerSala($id)); }
    public function salasUpdate(SalaRequest $req,$id){ return new SalaResource($this->service->actualizarSala($id,$req->validated())); }
    public function salasDelete($id){ $this->service->eliminarSala($id); return response()->json(['message'=>'Sala eliminada']); }

    // ---------- Permisos ----------
    public function permisosIndex(){ return PermisoResource::collection($this->service->listarPermisos()); }
    public function permisosStore(PermisoRequest $req){ return new PermisoResource($this->service->crearPermiso($req->validated())); }
    public function permisosShow($id){ return new PermisoResource($this->service->obtenerPermiso($id)); }
    public function permisosUpdate(PermisoRequest $req,$id){ return new PermisoResource($this->service->actualizarPermiso($id,$req->validated())); }
    public function permisosDelete($id){ $this->service->eliminarPermiso($id); return response()->json(['message'=>'Permiso eliminado']); }

}
