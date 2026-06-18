<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->string('name')->trim()->value(),
            email: $request->string('email')->trim()->value(),
            password: $request->string('password')->value(),
        );
    }
}
