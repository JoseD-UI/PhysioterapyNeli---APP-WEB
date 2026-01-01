<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreating = $this->isMethod('post');

        return [
            'nombre' => ($isCreating ? 'required' : 'sometimes') . '|string|max:150',
            'descripcion' => 'nullable|string|max:500',
        ];
    }
}
