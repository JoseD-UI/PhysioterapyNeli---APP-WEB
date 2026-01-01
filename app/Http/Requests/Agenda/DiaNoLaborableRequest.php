<?php
namespace App\Http\Requests\Agenda;
use Illuminate\Foundation\Http\FormRequest;

class DiaNoLaborableRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        return [
            'fecha' => 'required|date',
            'fisioterapeuta_id' => 'nullable|string|size:36|exists:principal_personas,persona_id',
            'motivo' => 'nullable|string|max:255'
        ];
    }
}
