<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->normalizeName($this->input('first_name', $this->input('fname'))),
            'middle_name' => $this->normalizeName($this->input('middle_name', $this->input('mname'))),
            'last_name' => $this->normalizeName($this->input('last_name', $this->input('lname'))),
            'username' => strtolower(trim((string) $this->input('username'))),
            'address' => trim((string) $this->input('address')),
            'contact' => preg_replace('/\D+/', '', (string) $this->input('contact', $this->input('contact_no'))),
            'email' => strtolower(trim((string) $this->input('email'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'address' => ['required', 'string', 'min:5', 'max:1000'],
            'contact' => ['required', 'digits:11'],
            'username' => ['required', 'string', 'min:4', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', 'unique:user_accounts,username'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:students,email',
                'unique:users,email',
                'unique:user_accounts,email',
            ],
            'degree_id' => ['required', 'integer', 'exists:degrees,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'degree_id' => 'degree',
            'contact' => 'contact number',
            'username' => 'username',
            'password_confirmation' => 'password confirmation',
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex' => 'The username may only contain letters, numbers, dots, underscores, and hyphens.',
        ];
    }

    private function normalizeName(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);

        return $normalized === '' ? null : $normalized;
    }
}
