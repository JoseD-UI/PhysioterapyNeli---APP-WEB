<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Model;

class FacturacionSerie extends Model
{
    protected $table = 'facturacion_series';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * SOLO datos administrativos
     * El correlativo NO se setea desde requests
     */
    protected $fillable = [
        'tipo_comprobante',   // 01,03,07,12
        'serie',              // F001, B001...
        'activo'
    ];

    public $timestamps = true;

    /* =====================================================
     | SCOPES
     ===================================================== */

    /**
     * Series activas
     */
    public function scopeActiva($query)
    {
        return $query->where('activo', 1);
    }

    /* =====================================================
     | RELACIONES
     ===================================================== */

    /**
     * Comprobantes numerados con esta serie
     */
    public function comprobantes()
    {
        return $this->hasMany(
            FacturacionComprobante::class,
            'serie',
            'serie'
        );
    }
}
