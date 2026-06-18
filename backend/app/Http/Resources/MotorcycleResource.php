<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotorcycleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'license_plate' => $this->license_plate,
            'engine_cc' => $this->engine_cc,
            'engine_type' => $this->engine_type,
            'transmission' => $this->transmission,
            'fuel_type' => $this->fuel_type,
            'purchase_date' => $this->purchase_date?->format('Y-m-d'),
            'odometer_km' => $this->odometer_km,
            'notes' => $this->notes,
            'status' => $this->status,
            'is_favorite' => $this->is_favorite,
            'maintenances_count' => $this->maintenances_count ?? $this->maintenances->count(),
            'troubleshooting_count' => $this->troubleshooting_histories_count ?? $this->troubleshootingHistories->count(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
