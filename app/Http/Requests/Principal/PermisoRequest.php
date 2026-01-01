<?php
namespace App\Http\Requests\Principal;
use Illuminate\Foundation\Http\FormRequest;

class PermisoRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        return [
            'codigo' => 'required|string|max:150|unique:principal_permisos,codigo,' . $this->route('permiso'),
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string'
        ];
    }
}

