<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class UpdateMotorcycleDTO
{
    public function __construct(
        public ?string $brand = null,
        public ?string $model = null,
        public ?int $year = null,
        public ?string $color = null,
        public ?string $license_plate = null,
        public ?int $engine_cc = null,
        public ?string $engine_type = null,
        public ?string $transmission = null,
        public ?string $fuel_type = null,
        public ?string $purchase_date = null,
        public ?int $odometer_km = null,
        public ?string $notes = null,
        public ?string $status = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            brand: $request->string('brand')->trim()->value() ?: null,
            model: $request->string('model')->trim()->value() ?: null,
            year: $request->integer('year') ?: null,
            color: $request->string('color')->trim()->value() ?: null,
            license_plate: $request->string('license_plate')->trim()->value() ?: null,
            engine_cc: $request->integer('engine_cc') ?: null,
            engine_type: $request->string('engine_type')->trim()->value() ?: null,
            transmission: $request->string('transmission')->trim()->value() ?: null,
            fuel_type: $request->string('fuel_type')->trim()->value() ?: null,
            purchase_date: $request->string('purchase_date')->value() ?: null,
            odometer_km: $request->integer('odometer_km') ?: null,
            notes: $request->string('notes')->trim()->value() ?: null,
            status: $request->string('status')->trim()->value() ?: null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'license_plate' => $this->license_plate,
            'engine_cc' => $this->engine_cc,
            'engine_type' => $this->engine_type,
            'transmission' => $this->transmission,
            'fuel_type' => $this->fuel_type,
            'purchase_date' => $this->purchase_date,
            'odometer_km' => $this->odometer_km,
            'notes' => $this->notes,
            'status' => $this->status,
        ], fn ($value) => !is_null($value));
    }
}
