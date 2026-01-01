<?php

namespace App\Services\Clinico;

use App\Repositories\Clinico\ClinicoRepository;
use Illuminate\Support\Facades\DB;

class ClinicoService
{
    protected $repo;

    public function __construct(ClinicoRepository $repo)
    {
        $this->repo = $repo;
    }

    /* ======= SERVICIOS ======= */

    public function listarServicios()
    {
        return $this->repo->listarServicios();
    }

    public function crearServicio(array $data)
    {
        return DB::transaction(fn() => $this->repo->crearServicio($data));
    }

    public function obtenerServicio($id)
    {
        return $this->repo->obtenerServicio($id);
    }

    public function actualizarServicio($id, array $data)
    {
        return DB::transaction(fn() => $this->repo->actualizarServicio($id, $data));
    }

    public function eliminarServicio($id)
    {
        return $this->repo->eliminarServicio($id);
    }

    /* ======= HISTORIAS ======= */

    public function listarHistorias()
    {
        return $this->repo->listarHistorias();
    }

    public function crearHistoria(array $data)
    {
        return DB::transaction(fn() => $this->repo->crearHistoria($data));
    }

    public function obtenerHistoria($id)
    {
        return $this->repo->obtenerHistoria($id);
    }

    public function actualizarHistoria($id, array $data)
    {
        return DB::transaction(fn() => $this->repo->actualizarHistoria($id, $data));
    }

    public function eliminarHistoria($id)
    {
        return $this->repo->eliminarHistoria($id);
    }

    /* ======= SESIONES ======= */

    public function listarSesiones()
    {
        return $this->repo->listarSesiones();
    }

    public function crearSesion(array $data)
    {
        return DB::transaction(fn() => $this->repo->crearSesion($data));
    }

    public function obtenerSesion($id)
    {
        return $this->repo->obtenerSesion($id);
    }

    public function actualizarSesion($id, array $data)
    {
        return DB::transaction(fn() => $this->repo->actualizarSesion($id, $data));
    }

    public function eliminarSesion($id)
    {
        return $this->repo->eliminarSesion($id);
    }

    // TIPOS SERVICIO
    public function listarTiposServicio(){ return $this->repo->listarTiposServicio(); }
    public function crearTipoServicio(array $data){ return DB::transaction(fn()=> $this->repo->crearTipoServicio($data)); }
    public function obtenerTipoServicio($id){ return $this->repo->obtenerTipoServicio($id); }
    public function actualizarTipoServicio($id,array $data){ return DB::transaction(fn()=> $this->repo->actualizarTipoServicio($id,$data)); }
    public function eliminarTipoServicio($id){ return $this->repo->eliminarTipoServicio($id); }

}
