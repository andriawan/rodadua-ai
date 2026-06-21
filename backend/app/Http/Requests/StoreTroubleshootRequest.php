<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTroubleshootRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motorcycle_id' => ['required', 'integer', 'exists:motorcycles,id'],
            'problem_description' => ['required', 'string', 'max:2000'],
            'symptoms' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
