<?php

namespace App\Http\Controllers\Api\Clinico;

use App\Http\Controllers\Controller;
use App\Services\Clinico\ClinicoService;

use App\Http\Requests\Clinico\ServicioRequest;
use App\Http\Requests\Clinico\HistoriaClinicaRequest;
use App\Http\Requests\Clinico\SesionRequest;

use App\Http\Resources\Clinico\ServicioResource;
use App\Http\Resources\Clinico\HistoriaClinicaResource;
use App\Http\Resources\Clinico\SesionResource;
use Illuminate\Http\Request;
use App\Http\Requests\Clinico\TipoServicioRequest;
use App\Http\Resources\Clinico\TipoServicioResource;

class ClinicoController extends Controller
{
    protected $service;

    public function __construct(ClinicoService $service)
    {
        $this->service = $service;
    }

    /* ============================
       SERVICIOS
    ============================= */

    public function serviciosIndex()
    {
        return ServicioResource::collection($this->service->listarServicios());
    }

    public function serviciosStore(ServicioRequest $request)
    {
        return new ServicioResource($this->service->crearServicio($request->validated()));
    }

    public function serviciosShow($id)
    {
        return new ServicioResource($this->service->obtenerServicio($id));
    }

    public function serviciosUpdate(ServicioRequest $request, $id)
    {
        return new ServicioResource($this->service->actualizarServicio($id, $request->validated()));
    }

    public function serviciosDelete($id)
    {
        $this->service->eliminarServicio($id);
        return response()->json(['message' => 'Servicio eliminado']);
    }

    /* ============================
       HISTORIAS CLÍNICAS
    ============================= */

    public function historiasIndex()
    {
        return HistoriaClinicaResource::collection($this->service->listarHistorias());
    }

    public function historiasStore(HistoriaClinicaRequest $request)
    {
        return new HistoriaClinicaResource($this->service->crearHistoria($request->validated()));
    }

    public function historiasShow($id)
    {
        return new HistoriaClinicaResource($this->service->obtenerHistoria($id));
    }

    public function historiasUpdate(HistoriaClinicaRequest $request, $id)
    {
        return new HistoriaClinicaResource($this->service->actualizarHistoria($id, $request->validated()));
    }

    public function historiasDelete($id)
    {
        $this->service->eliminarHistoria($id);
        return response()->json(['message' => 'Historia clínica eliminada']);
    }

    /* ============================
       SESIONES
    ============================= */

    public function sesionesIndex()
    {
        return SesionResource::collection($this->service->listarSesiones());
    }

    public function sesionesStore(SesionRequest $request)
    {
        return new SesionResource($this->service->crearSesion($request->validated()));
    }

    public function sesionesShow($id)
    {
        return new SesionResource($this->service->obtenerSesion($id));
    }

    public function sesionesUpdate(SesionRequest $request, $id)
    {
        return new SesionResource($this->service->actualizarSesion($id, $request->validated()));
    }

    public function sesionesDelete($id)
    {
        $this->service->eliminarSesion($id);
        return response()->json(['message' => 'Sesión eliminada']);
    }

    // Tipos Servicio
    public function tiposIndex(){ return TipoServicioResource::collection($this->service->listarTiposServicio()); }
    public function tiposStore(TipoServicioRequest $req){ return new TipoServicioResource($this->service->crearTipoServicio($req->validated())); }
    public function tiposShow($id){ return new TipoServicioResource($this->service->obtenerTipoServicio($id)); }
    public function tiposUpdate(TipoServicioRequest $req,$id){ return new TipoServicioResource($this->service->actualizarTipoServicio($id,$req->validated())); }
    public function tiposDelete($id){ $this->service->eliminarTipoServicio($id); return response()->json(['message'=>'Tipo de servicio eliminado']); }

}
