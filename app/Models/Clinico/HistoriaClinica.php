<?php

namespace App\Models\Clinico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HistoriaClinica extends Model
{
    protected $table = 'clinico_historias_clinicas';
    protected $primaryKey = 'historia_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // tiene creado_en y actualizado_en
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    protected $fillable = [
        'historia_id',
        'persona_id',
        'motivo_consulta',
        'antecedentes',
        'alergias',
        'diagnostico_inicial',
        'recomendaciones',
        'creado_en',
        'actualizado_en',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function paciente()
    {
        return $this->belongsTo(\App\Models\Principal\Persona::class, 'persona_id', 'persona_id');
    }
}

