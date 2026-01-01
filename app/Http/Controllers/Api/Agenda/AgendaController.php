<?php

namespace App\Http\Controllers\Api\Agenda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Agenda\AgendaService;
use App\Http\Requests\Agenda\CitaRequest;
use App\Http\Requests\Agenda\CitaEstadoRequest;
use App\Http\Requests\Agenda\HorarioRequest;
use App\Http\Requests\Agenda\DiaNoLaborableRequest;

use App\Http\Resources\Agenda\CitaResource;
use App\Http\Resources\Agenda\CitaEstadoResource;
use App\Http\Resources\Agenda\HorarioResource;
use App\Http\Resources\Agenda\DiaNoLaborableResource;

use Illuminate\Validation\ValidationException;

class AgendaController extends Controller
{
    protected $service;
    public function __construct(AgendaService $service) { $this->service = $service; }

    // ---------- CITAS ----------
    public function citasIndex(Request $r)
    {
        $filters = $r->only(['paciente_id','fisioterapeuta_id','sala_id','fecha_desde','fecha_hasta']);
        $citas = $this->service->listarCitas($filters);
        return CitaResource::collection($citas);
    }

    public function citasStore(CitaRequest $req)
    {
        try {
            $cita = $this->service->crearCita($req->validated());
            return new CitaResource($cita);
        } catch (ValidationException $e) {
            return response()->json(['errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()],400);
        }
    }

    public function citasShow($id)
    {
        $c = $this->service->obtenerCita($id);
        if (!$c) return response()->json(['message'=>'Cita no encontrada'],404);
        return new CitaResource($c);
    }

    public function citasUpdate(CitaRequest $req,$id)
    {
        try {
            $cita = $this->service->actualizarCita($id, $req->validated());
            return new CitaResource($cita);
        } catch (ValidationException $e) {
            return response()->json(['errors'=>$e->errors()],422);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()],400);
        }
    }

    public function citasDelete($id)
    {
        $ok = $this->service->eliminarCita($id);
        if (!$ok) return response()->json(['message'=>'No se pudo eliminar la cita o no existe'],400);
        return response()->json(['message'=>'Cita eliminada correctamente']);
    }

    // ---------- ESTADOS ----------
    public function estadosIndex() { return CitaEstadoResource::collection($this->service->listarEstados()); }
    public function estadosStore(CitaEstadoRequest $req) { return new CitaEstadoResource($this->service->crearEstado($req->validated())); }
    public function estadosShow($id) { $e = $this->service->obtenerEstado($id); if(!$e) return response()->json(['message'=>'Estado no encontrado'],404); return new CitaEstadoResource($e); }
    public function estadosUpdate(CitaEstadoRequest $req,$id) { return new CitaEstadoResource($this->service->actualizarEstado($id,$req->validated())); }
    public function estadosDelete($id) { $ok = $this->service->eliminarEstado($id); if(!$ok) return response()->json(['message'=>'No se eliminó'],400); return response()->json(['message'=>'Estado eliminado']); }

    // ---------- HORARIOS ----------
    public function horariosIndex(Request $r) { $filters = $r->only(['fisioterapeuta_id']); return HorarioResource::collection($this->service->listarHorarios($filters)); }
    public function horariosStore(HorarioRequest $req) {
        try { return new HorarioResource($this->service->crearHorario($req->validated())); }
        catch (ValidationException $e) { return response()->json(['errors'=>$e->errors()],422); }
        catch (\Exception $e) { return response()->json(['message'=>$e->getMessage()],400); }
    }
    public function horariosShow($id) { $h = $this->service->obtenerHorario($id); if(!$h) return response()->json(['message'=>'Horario no encontrado'],404); return new HorarioResource($h); }
    public function horariosUpdate(HorarioRequest $req,$id) { return new HorarioResource($this->service->actualizarHorario($id,$req->validated())); }
    public function horariosDelete($id) { $ok = $this->service->eliminarHorario($id); if(!$ok) return response()->json(['message'=>'No se eliminó'],400); return response()->json(['message'=>'Horario eliminado']); }

    // ---------- DIAS NO LABORABLES ----------
    public function diasIndex(Request $r) { $filters = $r->only(['fisioterapeuta_id']); return DiaNoLaborableResource::collection($this->service->listarDiasNoLaborables($filters)); }
    public function diasStore(DiaNoLaborableRequest $req) {
        try { return new DiaNoLaborableResource($this->service->crearDiaNoLaborable($req->validated())); }
        catch (ValidationException $e) { return response()->json(['errors'=>$e->errors()],422); }
        catch (\Exception $e) { return response()->json(['message'=>$e->getMessage()],400); }
    }
    public function diasShow($id) { $d = $this->service->obtenerDiaNoLaborable($id); if(!$d) return response()->json(['message'=>'No encontrado'],404); return new DiaNoLaborableResource($d); }
    public function diasUpdate(DiaNoLaborableRequest $req,$id) { return new DiaNoLaborableResource($this->service->actualizarDiaNoLaborable($id,$req->validated())); }
    public function diasDelete($id) { $ok = $this->service->eliminarDiaNoLaborable($id); if(!$ok) return response()->json(['message'=>'No se eliminó'],400); return response()->json(['message'=>'Día no laborable eliminado']); }
}
