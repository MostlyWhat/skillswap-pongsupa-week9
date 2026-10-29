<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:100'],
            'category' => ['required', 'string', 'min:2', 'max:50'],
            'price' => ['required', 'numeric', 'min:0', 'max:1000'],
            'description' => ['required', 'string', 'min:10', 'max:500'],
            'provider' => ['nullable', 'string', 'max:100'],
        ];
    }
}
