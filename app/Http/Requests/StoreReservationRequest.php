<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date'      => ['required', 'date', 'after_or_equal:today'],
            'end_date'        => ['required', 'date', 'after:start_date'],
            'passenger_count' => ['required', 'integer', 'min:1'],
            'extras'          => ['nullable', 'array'],
            'extras.*'        => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required'      => 'La fecha de inicio es obligatoria.',
            'start_date.after_or_equal'=> 'La fecha de inicio no puede ser en el pasado.',
            'end_date.required'        => 'La fecha de fin es obligatoria.',
            'end_date.after'           => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'passenger_count.required' => 'Indica la cantidad de pasajeros.',
            'passenger_count.min'      => 'La cantidad mínima es 1 pasajero.',
        ];
    }
}
