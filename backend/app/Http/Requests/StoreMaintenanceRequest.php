<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motorcycle_id' => ['required', 'integer', 'exists:motorcycles,id'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'odometer_km' => ['required', 'integer', 'min:0'],
            'maintenance_date' => ['required', 'date'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'workshop' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:scheduled,completed,pending'],
            'next_maintenance_km' => ['nullable', 'integer', 'min:0'],
            'next_maintenance_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
