<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'        => ['required', 'exists:categories,id'],
            'brand'              => ['required', 'string', 'max:255'],
            'model'              => ['required', 'string', 'max:255'],
            'model_alternative'  => ['nullable', 'string', 'max:255'],
            'year'               => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'plate'              => ['required', 'string', 'max:20', 'unique:vehicles,plate,' . $this->vehicle->id],
            'price_per_day'      => ['required', 'numeric', 'min:0'],
            'status'             => ['required', 'in:disponible,alquilado,mantenimiento'],
            'transmission_type'  => ['required', 'in:manual,automatica'],
            'fuel_type'          => ['required', 'in:gasolina,diesel,hibrido,electrico'],
            'passenger_capacity' => ['required', 'integer', 'min:1'],
            'luggage_capacity'   => ['nullable', 'integer', 'min:0'],
            'key_features'       => ['nullable', 'string', 'max:1000'],
            'current_mileage'    => ['required', 'integer', 'min:0'],
            'current_fuel_level' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'        => 'Debe seleccionar una categoría.',
            'category_id.exists'          => 'La categoría seleccionada no existe.',
            'plate.required'              => 'La placa es obligatoria.',
            'plate.unique'               => 'Ya existe un vehículo con esa placa.',
            'price_per_day.required'      => 'El precio por día es obligatorio.',
            'price_per_day.numeric'       => 'El precio debe ser un número.',
            'status.in'                   => 'El estado debe ser: disponible, alquilado o mantenimiento.',
            'transmission_type.in'        => 'La transmisión debe ser manual o automática.',
            'fuel_type.in'               => 'El combustible debe ser gasolina, diésel, híbrido o eléctrico.',
            'passenger_capacity.required' => 'La capacidad de pasajeros es obligatoria.',
            'passenger_capacity.min'      => 'La capacidad mínima es 1 pasajero.',
            'current_fuel_level.max'      => 'El nivel de combustible no puede superar 100.',
        ];
    }
}
