<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class TroubleshootDTO
{
    public function __construct(
        public readonly int $motorcycleId,
        public readonly string $problemDescription,
        public readonly ?string $symptoms,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            motorcycleId: (int) $request->input('motorcycle_id'),
            problemDescription: $request->input('problem_description'),
            symptoms: $request->input('symptoms'),
        );
    }

    public function toArray(): array
    {
        return [
            'motorcycle_id' => $this->motorcycleId,
            'problem_description' => $this->problemDescription,
            'symptoms' => $this->symptoms,
        ];
    }
}
