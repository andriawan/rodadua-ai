<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'color' => ['nullable', 'string', 'max:255'],
            'license_plate' => ['nullable', 'string', 'max:255', 'unique:motorcycles'],
            'engine_cc' => ['nullable', 'integer', 'min:0'],
            'engine_type' => ['nullable', 'string', 'max:255'],
            'transmission' => ['nullable', 'string', 'in:automatic,manual,cvt'],
            'fuel_type' => ['nullable', 'string', 'in:petrol,diesel,electric,hybrid'],
            'purchase_date' => ['nullable', 'date'],
            'odometer_km' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
