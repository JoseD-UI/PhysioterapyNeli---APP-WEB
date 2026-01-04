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
        'actualizado_en',
    ];

    // Constantes para Tipo de Persona
    const TIPO_PACIENTE_NATURAL = 'PACIENTE-NATURAL';
    const TIPO_PACIENTE_JURIDICA = 'PACIENTE-JURIDICA';
    const TIPO_ADMINISTRATIVO = 'ADMINISTRATIVO';

    // Constantes para Documentos
    const TIPO_DOC_DNI = 'DNI';
    const TIPO_DOC_CARNET_EXT = 'CARNET_EXT';
    const TIPO_DOC_RUC = 'RUC';

    /**
     * Check if the person profile is complete based on their type.
     */
    public function isProfileComplete(): bool
    {
        // Basic fields required for everyone
        if (empty($this->direccion) || empty($this->telefono)) {
            return false;
        }

        // Specific logic based on type
        if ($this->tipo_persona === self::TIPO_PACIENTE_JURIDICA) {
            // RUC (11 digits) required
            if ($this->ruc_len() !== 11) return false;
            // Name (Razón Social) required
            if (empty($this->nombres)) return false;
        } else {
            // Natural Person (DNI 8 or Carnet 9)
            if (empty($this->dni) && empty($this->ruc) && empty($this->documento_numero)) return false; // assuming validated in controller
            if (empty($this->nombres) || empty($this->apellidos)) return false;
            if (empty($this->fecha_nacimiento)) return false;
        }

        return true;
    }

    // Helper to check RUC length if stored in 'ruc' column or generic column
    // Note: Schema has 'ruc' and 'dni' columns, but plan mentions generic 'documento_numero'. 
    // Checking schema: It has 'dni' and 'ruc' columns specifically.
    private function ruc_len() {
        return strlen($this->ruc ?? '');
    }

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