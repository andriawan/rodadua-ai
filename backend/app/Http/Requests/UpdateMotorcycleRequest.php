<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMotorcycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('motorcycle')->user_id;
    }

    public function rules(): array
    {
        return [
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'color' => ['nullable', 'string', 'max:255'],
            'license_plate' => ['nullable', 'string', 'max:255', 'unique:motorcycles,license_plate,' . $this->route('motorcycle')->id],
            'engine_cc' => ['nullable', 'integer', 'min:0'],
            'engine_type' => ['nullable', 'string', 'max:255'],
            'transmission' => ['nullable', 'string', 'in:automatic,manual,cvt'],
            'fuel_type' => ['nullable', 'string', 'in:petrol,diesel,electric,hybrid'],
            'purchase_date' => ['nullable', 'date'],
            'odometer_km' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'string', 'in:active,inactive,for_sale'],
        ];
    }
}
