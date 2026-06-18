<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->string('email')->trim()->value(),
            password: $request->string('password')->value(),
        );
    }
}
