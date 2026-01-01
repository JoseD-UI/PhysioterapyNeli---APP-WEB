<?php
namespace App\Http\Requests\Principal;
use Illuminate\Foundation\Http\FormRequest;

class SalaRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        return [
            'nombre_sala' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'activa' => 'boolean'
        ];
    }
}

