<?php

namespace App\Observers;

use App\Services\Seguridad\AuditLogService;

class AuditObserver
{
    public function __construct(
        protected AuditLogService $service
    ) {}

    public function created($model)
    {
        $this->service->registrar([
            'tabla_nombre' => $model->getTable(),
            'operacion'    => 'INSERT',
            'registro_id'  => $model->getKey(),
            'datos_nuevos' => $model->getAttributes(),
            'descripcion'  => 'Registro creado',
        ]);
    }

    public function updated($model)
    {
        $this->service->registrar([
            'tabla_nombre'  => $model->getTable(),
            'operacion'     => 'UPDATE',
            'registro_id'   => $model->getKey(),
            'datos_previos' => $model->getOriginal(),
            'datos_nuevos'  => $model->getAttributes(),
            'descripcion'   => 'Registro actualizado',
        ]);
    }

    public function deleted($model)
    {
        $this->service->registrar([
            'tabla_nombre'  => $model->getTable(),
            'operacion'     => 'DELETE',
            'registro_id'   => $model->getKey(),
            'datos_previos' => $model->getOriginal(),
            'descripcion'   => 'Registro eliminado',
        ]);
    }
}
