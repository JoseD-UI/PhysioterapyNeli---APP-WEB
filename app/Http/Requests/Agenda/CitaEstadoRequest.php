<?php
namespace App\Http\Requests\Agenda;
use Illuminate\Foundation\Http\FormRequest;

class CitaEstadoRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        $isUpdate = in_array($this->method(), ['PUT','PATCH']);
        return [
            'codigo' => 'required|string|max:50|unique:agenda_cita_estados,codigo' . ($isUpdate ? (',' . $this->route('id')) : ''),
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ];
    }
}
