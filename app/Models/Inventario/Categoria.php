<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categoria extends Model
{
    protected $table = 'inventario_categorias';
    protected $primaryKey = 'categoria_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;




    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'activo'
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }

    public function items()
    {
        return $this->hasMany(\App\Models\Inventario\Item::class, 'categoria_id', 'categoria_id');
    }
}
