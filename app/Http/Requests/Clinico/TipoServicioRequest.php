<?php
namespace App\Http\Requests\Clinico;
use Illuminate\Foundation\Http\FormRequest;

class TipoServicioRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ];
    }
}

