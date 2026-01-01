<?php

namespace App\Repositories\Clinico;

use App\Models\Clinico\Servicio;
use App\Models\Clinico\HistoriaClinica;
use App\Models\Clinico\Sesion;
use App\Models\Clinico\TipoServicio;
class ClinicoRepository
{
    /* ==============================
       SERVICIOS
    =============================== */

    public function listarServicios()
    {
        return Servicio::orderBy('creado_en', 'desc')->get();
    }

    public function crearServicio(array $data)
    {
        return Servicio::create($data);
    }

    public function obtenerServicio($id)
    {
        return Servicio::find($id);
    }

    public function actualizarServicio($id, array $data)
    {
        $servicio = Servicio::find($id);
        if (!$servicio) return null;

        $servicio->update($data);
        return $servicio;
    }

    public function eliminarServicio($id)
    {
        $servicio = Servicio::find($id);
        return $servicio?->delete();
    }

    /* ==============================
       HISTORIAS CLÍNICAS
    =============================== */

    public function listarHistorias()
    {
        return HistoriaClinica::with('persona')->orderBy('creado_en', 'desc')->get();
    }

    public function crearHistoria(array $data)
    {
        return HistoriaClinica::create($data);
    }

    public function obtenerHistoria($id)
    {
        return HistoriaClinica::find($id);
    }

    public function actualizarHistoria($id, array $data)
    {
        $historia = HistoriaClinica::find($id);
        if (!$historia) return null;

        $historia->update($data);
        return $historia;
    }

    public function eliminarHistoria($id)
    {
        $historia = HistoriaClinica::find($id);
        return $historia?->delete();
    }

    /* ==============================
       SESIONES
    =============================== */

    public function listarSesiones()
    {
        return Sesion::with('paciente', 'fisioterapeuta', 'servicio')->orderBy('creado_en', 'desc')->get();
    }

    public function crearSesion(array $data)
    {
        return Sesion::create($data);
    }

    public function obtenerSesion($id)
    {
        return Sesion::find($id);
    }

    public function actualizarSesion($id, array $data)
    {
        $sesion = Sesion::find($id);
        if (!$sesion) return null;

        $sesion->update($data);
        return $sesion;
    }

    public function eliminarSesion($id)
    {
        $sesion = Sesion::find($id);
        return $sesion?->delete();
    }

    // TIPOS SERVICIO
    public function listarTiposServicio(){ return TipoServicio::orderBy('nombre')->get(); }
    public function crearTipoServicio(array $data){ return TipoServicio::create($data); }
    public function obtenerTipoServicio($id){ return TipoServicio::find($id); }
    public function actualizarTipoServicio($id, array $data){ $t = TipoServicio::find($id); if(!$t) return null; $t->update($data); return $t; }
    public function eliminarTipoServicio($id){ $t = TipoServicio::find($id); return $t ? $t->delete() : false; }

}
