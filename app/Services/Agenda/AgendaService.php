<?php

namespace App\Services\Agenda;

use App\Repositories\Agenda\AgendaRepository;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Models\Agenda\CitaEstado;

class AgendaService
{
    protected $repo;

    public function __construct(AgendaRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Listar citas / entidades
     */
    public function listarCitas(array $filters = []) { return $this->repo->listarCitas($filters); }
    public function obtenerCita(string $id) { return $this->repo->obtenerCita($id); }

    /**
     * Crear cita (valida y crea). $data debe contener timezone opcional.
     */
    public function crearCita(array $data)
    {
        // timezone -> convertir a UTC
        $tz = $data['timezone'] ?? config('appointments.default_timezone', config('app.timezone','UTC'));
        $inicioUtc = Carbon::parse($data['fecha_inicio'], $tz)->setTimezone('UTC')->toDateTimeString();
        $finUtc = Carbon::parse($data['fecha_fin'], $tz)->setTimezone('UTC')->toDateTimeString();

        // Reglas de negocio (ordenadas):

        // 1) paciente/fisio/servicio existencia: Request ya valida exists, defensiva opcional
        if (empty($data['paciente_id']) || empty($data['fisioterapeuta_id'])) {
            throw ValidationException::withMessages(['general' => ['Paciente y fisioterapeuta son requeridos.']]);
        }

        // 2) dia no laborable?
        if ($this->repo->esDiaNoLaborable($inicioUtc, $data['fisioterapeuta_id'])) {
            throw ValidationException::withMessages(['fecha_inicio'=>['El día es no laborable para este fisioterapeuta o para el centro.']]);
        }

        // 3) horario del fisioterapeuta cubre intervalo?
        if (!$this->repo->horarioCubreIntervalo($data['fisioterapeuta_id'], $inicioUtc, $finUtc)) {
            throw ValidationException::withMessages(['fecha_inicio'=>['El fisioterapeuta no trabaja en ese intervalo.']]);
        }

        // 4) solapamiento por fisio o sala
        if ($this->repo->existeSolapamiento($data['fisioterapeuta_id'] ?? null, $data['sala_id'] ?? null, $inicioUtc, $finUtc)) {
            throw ValidationException::withMessages(['fecha_inicio'=>['El horario se solapa con otra cita (fisioterapeuta o sala).']]);
        }

        // 5) obtener estado inicial
        $estado = $this->repo->getEstadoInicial();
        if (!$estado) throw ValidationException::withMessages(['estado'=>['No hay estado inicial configurado.']]);

        // 6) crear payload y persistir (guardamos UTC)
        $payload = [
            'paciente_id' => $data['paciente_id'],
            'fisioterapeuta_id' => $data['fisioterapeuta_id'],
            'servicio_id' => $data['servicio_id'] ?? null,
            'sala_id' => $data['sala_id'] ?? null,
            'fecha_inicio' => $inicioUtc,
            'fecha_fin' => $finUtc,
            'estado_id' => $estado->estado_id,
            'creado_por' => $data['creado_por'] ?? null
        ];

        return $this->repo->crearCita($payload);
    }

    /**
     * Actualizar cita con validaciones (excluye la propia cita al validar solapamiento)
     */
    public function actualizarCita(string $id, array $data)
    {
        $cita = $this->repo->obtenerCita($id);
        if (!$cita) throw ValidationException::withMessages(['cita'=>['Cita no encontrada']]);

        $tz = $data['timezone'] ?? config('appointments.default_timezone', config('app.timezone','UTC'));
        $inicioUtc = Carbon::parse($data['fecha_inicio'], $tz)->setTimezone('UTC')->toDateTimeString();
        $finUtc = Carbon::parse($data['fecha_fin'], $tz)->setTimezone('UTC')->toDateTimeString();

        $fisioterapeutaId = $data['fisioterapeuta_id'] ?? $cita->fisioterapeuta_id;
        $salaId = $data['sala_id'] ?? $cita->sala_id;

        if ($this->repo->esDiaNoLaborable($inicioUtc, $fisioterapeutaId)) {
            throw ValidationException::withMessages(['fecha_inicio'=>['El día es no laborable para este fisioterapeuta o para el centro.']]);
        }

        if (!$this->repo->horarioCubreIntervalo($fisioterapeutaId, $inicioUtc, $finUtc)) {
            throw ValidationException::withMessages(['fecha_inicio'=>['El fisioterapeuta no trabaja en ese intervalo.']]);
        }

        if ($this->repo->existeSolapamiento($fisioterapeutaId, $salaId, $inicioUtc, $finUtc, $id)) {
            throw ValidationException::withMessages(['fecha_inicio'=>['El horario se solapa con otra cita (fisioterapeuta o sala).']]);
        }

        // validar estado_id si viene
        if (!empty($data['estado_id'])) {
            $estado = CitaEstado::where('estado_id',$data['estado_id'])->where('activo',1)->first();
            if (!$estado) throw ValidationException::withMessages(['estado_id'=>['Estado inválido o inactivo']]);
        }

        $payload = [
            'paciente_id' => $data['paciente_id'] ?? $cita->paciente_id,
            'fisioterapeuta_id' => $fisioterapeutaId,
            'servicio_id' => $data['servicio_id'] ?? $cita->servicio_id,
            'sala_id' => $salaId,
            'fecha_inicio' => $inicioUtc,
            'fecha_fin' => $finUtc,
            'estado_id' => $data['estado_id'] ?? $cita->estado_id,
            'creado_por' => $data['creado_por'] ?? $cita->creado_por
        ];

        return $this->repo->actualizarCita($id,$payload);
    }

    public function eliminarCita(string $id) { return $this->repo->eliminarCita($id); }

    // ---- Estados ----
    public function listarEstados() { return $this->repo->listarEstados(); }
    public function obtenerEstado(string $id) { return $this->repo->obtenerEstado($id); }
    public function crearEstado(array $data) { return $this->repo->crearEstado($data); }
    public function actualizarEstado(string $id, array $data) { return $this->repo->actualizarEstado($id,$data); }
    public function eliminarEstado(string $id) { return $this->repo->eliminarEstado($id); }

    // ---- Horarios ----
    public function listarHorarios($filters = []) { return $this->repo->listarHorarios($filters); }
    public function obtenerHorario(string $id) { return $this->repo->obtenerHorario($id); }
    public function crearHorario(array $data)
    {
        // Validación: hora_inicio < hora_fin y día válido 0..6 y que el fisio exista (Request lo valida)
        $hInicio = Carbon::createFromFormat('H:i:s', $data['hora_inicio']);
        $hFin = Carbon::createFromFormat('H:i:s', $data['hora_fin']);
        if ($hFin <= $hInicio) throw ValidationException::withMessages(['hora_fin'=>['hora_fin debe ser mayor que hora_inicio']]);

        return $this->repo->crearHorario($data);
    }
    public function actualizarHorario(string $id, array $data) { return $this->repo->actualizarHorario($id,$data); }
    public function eliminarHorario(string $id) { return $this->repo->eliminarHorario($id); }

    // ---- Dias no laborables ----
    public function listarDiasNoLaborables($filters = []) { return $this->repo->listarDiasNoLaborables($filters); }
    public function obtenerDiaNoLaborable(string $id) { return $this->repo->obtenerDiaNoLaborable($id); }
    public function crearDiaNoLaborable(array $data)
    {
        // Validar fecha no repetida para mismo fisioterapeuta (o global)
        $existe = $this->repo->listarDiasNoLaborables(['fisioterapeuta_id' => $data['fisioterapeuta_id'] ?? null])
            ->where('fecha', $data['fecha'])->count() > 0;
        if ($existe) throw ValidationException::withMessages(['fecha'=>['Ya existe un día no laborable para esa fecha y fisioterapeuta']]);

        return $this->repo->crearDiaNoLaborable($data);
    }
    public function actualizarDiaNoLaborable(string $id, array $data) { return $this->repo->actualizarDiaNoLaborable($id,$data); }
    public function eliminarDiaNoLaborable(string $id) { return $this->repo->eliminarDiaNoLaborable($id); }
}
