<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class ComparisonDTO
{
    public function __construct(
        public readonly int $motorcycleId,
        public readonly int $comparedMotorcycleId,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            motorcycleId: (int) $request->route('motorcycle'),
            comparedMotorcycleId: (int) $request->route('comparedMotorcycle'),
        );
    }

    public function toArray(): array
    {
        return [
            'motorcycle_id' => $this->motorcycleId,
            'compared_motorcycle_id' => $this->comparedMotorcycleId,
        ];
    }
}
