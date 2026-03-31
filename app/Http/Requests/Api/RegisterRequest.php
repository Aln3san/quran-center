<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->whereNull('deleted_at'),
            ],
            // role will default to 'student' if not provided; validate it exists in roles table
            // Use guard-aware check for Spatie permissions (roles table has guard_name)
            'role' => [
                'string',
                Rule::exists('roles', 'name')
                    ->where('guard_name', config('auth.defaults.guard')),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     * Ensure a default role of "student" when none is provided by the client.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => $this->input('role') ?? 'student',
        ]);
    }
}
