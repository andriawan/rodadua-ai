<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class MaintenanceDTO
{
    public function __construct(
        public readonly int $motorcycleId,
        public readonly ?array $history,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            motorcycleId: (int) $request->input('motorcycle_id'),
            history: $request->input('history'),
        );
    }

    public function toArray(): array
    {
        return [
            'motorcycle_id' => $this->motorcycleId,
            'history' => $this->history,
        ];
    }
}
