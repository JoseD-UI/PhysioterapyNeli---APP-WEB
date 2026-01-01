<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentoSunat extends Model
{
    protected $table = 'facturacion_documentos_sunat';
    protected $primaryKey = 'documento_sunat_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * SOLO campos controlados por procesos internos (SunatService / Jobs)
     * NO por requests
     */
    protected $fillable = [
        'documento_sunat_id',
        'comprobante_id',

        'tipo_comprobante',
        'serie',
        'correlativo',
    ];

    /**
     * Campos protegidos explícitamente
     */
    protected $guarded = [
        'xml_enviado',
        'xml_respuesta',
        'cdr_estado',
        'cdr_descripcion',
        'fecha_envio',
        'fecha_respuesta',
        'created_at',
        'updated_at',
    ];

    /**
     * Casts para auditoría y reportes
     */
    protected $casts = [
        'fecha_envio'     => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->documento_sunat_id) {
                $m->documento_sunat_id = (string) Str::uuid();
            }
        });
    }

    /* =====================================================
     | RELACIONES
     ===================================================== */

    public function comprobante()
    {
        return $this->belongsTo(
            FacturacionComprobante::class,
            'comprobante_id',
            'id'
        );
    }
}
