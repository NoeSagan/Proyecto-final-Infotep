<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255', 'unique:extras,name'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'El nombre del extra es obligatorio.',
            'name.unique'    => 'Ya existe un extra con ese nombre.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric'  => 'El precio debe ser un número.',
            'price.min'      => 'El precio no puede ser negativo.',
        ];
    }
}
