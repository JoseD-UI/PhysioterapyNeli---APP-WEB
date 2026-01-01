<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;

class UnidadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreating = $this->isMethod('post');

        return [
            'codigo' => ($isCreating ? 'required' : 'sometimes') . '|string|max:50',
            'nombre' => ($isCreating ? 'required' : 'sometimes') . '|string|max:100',
        ];
    }
}
