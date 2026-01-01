<?php


namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Persona extends Model
{
    protected $table = 'principal_personas';
    protected $primaryKey = 'persona_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Esta tabla tiene creado_en y actualizado_en
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    protected $fillable = [
        'persona_id',
        'tipo_persona',
        'nombres',
        'apellidos',
        'dni',
        'ruc',
        'fecha_nacimiento',
        'telefono',
        'email',
        'direccion',
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

    // Relaciones
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'persona_id', 'persona_id');
    }

    public function historias()
    {
        return $this->hasMany(\App\Models\Clinico\HistoriaClinica::class, 'persona_id', 'persona_id');
    }

    public function sesionesComoPaciente()
    {
        return $this->hasMany(\App\Models\Clinico\Sesion::class, 'paciente_id', 'persona_id');
    }

    public function sesionesComoFisioterapeuta()
    {
        return $this->hasMany(\App\Models\Clinico\Sesion::class, 'fisioterapeuta_id', 'persona_id');
    }

    public function citasComoPaciente()
    {
        return $this->hasMany(\App\Models\Agenda\Cita::class, 'paciente_id', 'persona_id');
    }

    public function citasComoFisioterapeuta()
    {
        return $this->hasMany(\App\Models\Agenda\Cita::class, 'fisioterapeuta_id', 'persona_id');
    }
}