<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FacturacionPago extends Model
{
    protected $table = 'facturacion_pagos';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'fecha_pago';
    const UPDATED_AT = null;

    /**
     * SOLO campos que pueden venir del dominio (Service)
     */
    protected $fillable = [
        'id',
        'comprobante_id',

        'paciente_id',
        'sesion_id',

        'medio_pago',
        'monto',

        'recibo',
        'referencia_externa',
    ];

    /**
     * Casts financieros y temporales
     */
    protected $casts = [
        'monto'      => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }

            if (empty($model->fecha_pago)) {
                $model->fecha_pago = now();
            }

            // Estado inicial controlado por sistema
            if (empty($model->estado_pago)) {
                $model->estado_pago = 'pagado';
            }
        });
    }

    /* =====================================================
     | RELACIONES
     ===================================================== */

    /**
     * Pago pertenece a un comprobante
     */
    public function comprobante()
    {
        return $this->belongsTo(
            FacturacionComprobante::class,
            'comprobante_id',
            'id'
        );
    }

    public function paciente()
    {
        return $this->belongsTo(
            \App\Models\Principal\Persona::class,
            'paciente_id',
            'persona_id'
        );
    }

    public function sesion()
    {
        return $this->belongsTo(
            \App\Models\Clinico\Sesion::class,
            'sesion_id',
            'sesion_id'
        );
    }
}
