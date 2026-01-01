<?php

namespace App\Models\Contabilidad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContabilidadLibroResumen extends Model
{
    protected $table = 'contabilidad_libros_resumen';
    protected $primaryKey = 'libro_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'libro_id',
        'tipo_libro',
        'periodo',
        'total_gravado',
        'total_igv',
        'total_general',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->libro_id) {
                $model->libro_id = (string) Str::uuid();
            }
        });
    }
}
