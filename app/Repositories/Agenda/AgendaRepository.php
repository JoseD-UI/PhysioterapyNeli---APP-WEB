<?php

namespace App\Repositories\Agenda;

use App\Models\Agenda\Cita;
use App\Models\Agenda\CitaEstado;
use App\Models\Agenda\HorarioFisioterapeuta;
use App\Models\Agenda\DiaNoLaborable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaRepository
{
    // -------- Citas CRUD ----------
    public function listarCitas(array $filters = [])
    {
        $q = Cita::with(['paciente','fisioterapeuta','servicio','sala','estado','creadoPor']);

        if (!empty($filters['paciente_id'])) $q->where('paciente_id', $filters['paciente_id']);
        if (!empty($filters['fisioterapeuta_id'])) $q->where('fisioterapeuta_id', $filters['fisioterapeuta_id']);
        if (!empty($filters['sala_id'])) $q->where('sala_id', $filters['sala_id']);
        if (!empty($filters['fecha_desde'])) $q->where('fecha_inicio','>=',$filters['fecha_desde']);
        if (!empty($filters['fecha_hasta'])) $q->where('fecha_fin','<=',$filters['fecha_hasta']);

        return $q->orderBy('fecha_inicio','asc')->get();
    }

    public function obtenerCita(string $id) { return Cita::with(['paciente','fisioterapeuta','servicio','sala','estado','creadoPor'])->find($id); }

    public function crearCita(array $data)
    {
        return DB::transaction(function() use ($data) {
            return Cita::create($data);
        });
    }

    public function actualizarCita(string $id, array $data)
    {
        return DB::transaction(function() use ($id, $data) {
            $c = Cita::find($id);
            if (!$c) return null;
            $c->update($data);
            return $c->fresh(['paciente','fisioterapeuta','servicio','sala','estado','creadoPor']);
        });
    }

    public function eliminarCita(string $id)
    {
        $c = Cita::find($id);
        return $c ? $c->delete() : false;
    }

    // -------- Estados CRUD ----------
    public function listarEstados() { return CitaEstado::orderBy('nombre')->get(); }
    public function obtenerEstado(string $id) { return CitaEstado::find($id); }
    public function crearEstado(array $data) { return CitaEstado::create($data); }
    public function actualizarEstado(string $id, array $data) { $e = CitaEstado::find($id); if(!$e) return null; $e->update($data); return $e; }
    public function eliminarEstado(string $id) { $e = CitaEstado::find($id); return $e ? $e->delete() : false; }

    // -------- Horarios CRUD ----------
    public function listarHorarios($filters = []) {
        $q = HorarioFisioterapeuta::query();
        if (!empty($filters['fisioterapeuta_id'])) $q->where('fisioterapeuta_id', $filters['fisioterapeuta_id']);
        return $q->orderBy('fisioterapeuta_id')->orderBy('dia_semana')->get();
    }
    public function obtenerHorario(string $id) { return HorarioFisioterapeuta::find($id); }
    public function crearHorario(array $data) { return HorarioFisioterapeuta::create($data); }
    public function actualizarHorario(string $id, array $data) { $h = HorarioFisioterapeuta::find($id); if(!$h) return null; $h->update($data); return $h; }
    public function eliminarHorario(string $id) { $h = HorarioFisioterapeuta::find($id); return $h ? $h->delete() : false; }

    // -------- Dias No Laborables CRUD ----------
    public function listarDiasNoLaborables($filters = []) {
        $q = DiaNoLaborable::query();
        if (!empty($filters['fisioterapeuta_id'])) $q->where('fisioterapeuta_id',$filters['fisioterapeuta_id']);
        return $q->orderBy('fecha','desc')->get();
    }
    public function obtenerDiaNoLaborable(string $id) { return DiaNoLaborable::find($id); }
    public function crearDiaNoLaborable(array $data) { return DiaNoLaborable::create($data); }
    public function actualizarDiaNoLaborable(string $id, array $data) { $d = DiaNoLaborable::find($id); if(!$d) return null; $d->update($data); return $d; }
    public function eliminarDiaNoLaborable(string $id) { $d = DiaNoLaborable::find($id); return $d ? $d->delete() : false; }

    // ===== Reglas de consulta/negocio reutilizables =====

    // Devuelve array de estado_id que bloquean (reservado|confirmado) según config
    protected function getBlockEstadoIds(): array
    {
        $codes = config('appointments.block_statuses', ['reservado','confirmado']);
        return CitaEstado::whereIn('codigo',$codes)->pluck('estado_id')->map(fn($v)=>(string)$v)->toArray();
    }

    /**
     * Verifica solapamiento por fisioterapeuta O sala.
     * $inicioUtc y $finUtc son strings en UTC.
     */
    public function existeSolapamiento(?string $fisioterapeutaId, ?string $salaId, string $inicioUtc, string $finUtc, ?string $excluirId = null): bool
    {
        $buffer = (int) config('appointments.buffer_minutes', 10);
        $inicio = Carbon::parse($inicioUtc, 'UTC')->subMinutes($buffer)->toDateTimeString();
        $fin = Carbon::parse($finUtc, 'UTC')->addMinutes($buffer)->toDateTimeString();

        $blockEstadoIds = $this->getBlockEstadoIds();

        $q = Cita::whereIn('estado_id', $blockEstadoIds)
            ->where(function($query) use ($fisioterapeutaId, $salaId) {
                if ($fisioterapeutaId) $query->where('fisioterapeuta_id', $fisioterapeutaId);
                if ($salaId) $query->orWhere('sala_id', $salaId);
            })
            ->where('fecha_inicio','<',$fin)
            ->where('fecha_fin','>',$inicio);

        if ($excluirId) $q->where('cita_id','!=',$excluirId);

        return $q->exists();
    }

    /**
     * Comprueba que el fisioterapeuta tiene un horario que cubre ese intervalo.
     */
    public function horarioCubreIntervalo(string $fisioterapeutaId, string $inicioUtc, string $finUtc): bool
    {
        $inicio = Carbon::parse($inicioUtc)->setTimezone('UTC');
        $fin = Carbon::parse($finUtc)->setTimezone('UTC');
        $dia = (int) $inicio->dayOfWeek; // 0..6
        $horaInicio = $inicio->format('H:i:s');
        $horaFin = $fin->format('H:i:s');

        return HorarioFisioterapeuta::where('fisioterapeuta_id',$fisioterapeutaId)
            ->where('dia_semana',$dia)
            ->where('activo',1)
            ->where('hora_inicio','<=',$horaInicio)
            ->where('hora_fin','>=',$horaFin)
            ->exists();
    }

    /**
     * Comprueba si una fecha es no laborable (global o por fisioterapeuta).
     */
    public function esDiaNoLaborable(string $fechaUtc, ?string $fisioterapeutaId = null): bool
    {
        $date = Carbon::parse($fechaUtc)->toDateString();

        $q = DiaNoLaborable::where('fecha',$date)
            ->where(function($q2) use ($fisioterapeutaId){
                $q2->whereNull('fisioterapeuta_id');
                if ($fisioterapeutaId) $q2->orWhere('fisioterapeuta_id',$fisioterapeutaId);
            });

        return $q->exists();
    }

    /**
     * Devuelve el estado inicial (objeto) por configuración (codigo default 'reservado').
     */
    public function getEstadoInicial()
    {
        $code = config('appointments.default_initial_state','reservado');
        return CitaEstado::where('codigo',$code)->first();
    }
}
