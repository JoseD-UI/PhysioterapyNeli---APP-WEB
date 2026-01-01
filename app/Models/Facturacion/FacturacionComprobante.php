<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FacturacionComprobante extends Model
{
    protected $table = 'facturacion_comprobantes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'fecha_emision';
    const UPDATED_AT = null;
    public $timestamps = true;

    /**
     * Campos que SÍ pueden asignarse desde el dominio (Service)
     */
    protected $fillable = [
        'id',
        'tipo_comprobante',   // 01,03,07,12
        'serie',
        'correlativo',

        'cliente_id',
        'ruc_cliente',
        'razon_social',
        'direccion_fiscal',

        'subtotal',
        'igv',
        'total',

        'estado',             // emitida | anulada
        'fecha_emision',
    ];

    /**
     * Casts contables y temporales
     */
    protected $casts = [
        'subtotal'        => 'decimal:2',
        'igv'             => 'decimal:2',
        'total'           => 'decimal:2',
        'fecha_emision'   => 'datetime',
        'fecha_anulacion' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) {
                $m->{$m->getKeyName()} = (string) Str::uuid();
            }

            if (empty($m->fecha_emision)) {
                $m->fecha_emision = now();
            }
        });
    }

    /* =====================================================
     | RELACIONES
     ===================================================== */

    /**
     * Un comprobante puede tener varios pagos
     */
    public function pagos()
    {
        return $this->hasMany(
            FacturacionPago::class,
            'comprobante_id',
            'id'
        );
    }

    /**
     * Detalles (items o servicios)
     */
    public function detalles()
    {
        return $this->hasMany(
            FacturacionDetalle::class,
            'comprobante_id',
            'id'
        );
    }

    /**
     * Documento SUNAT asociado (XML / CDR)
     */
    public function documentoSunat()
    {
        return $this->hasOne(
            DocumentoSunat::class,
            'comprobante_id',
            'id'
        );
    }
}
