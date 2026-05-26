<?php

namespace App\Http\Requests;

use App\Models\Student;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->normalizeName($this->input('first_name')),
            'middle_name' => $this->normalizeName($this->input('middle_name')),
            'last_name' => $this->normalizeName($this->input('last_name')),
            'username' => strtolower(trim((string) $this->input('username'))),
            'address' => trim((string) $this->input('address')),
            'contact' => preg_replace('/\D+/', '', (string) $this->input('contact')),
            'email' => strtolower(trim((string) $this->input('email'))),
        ]);
    }

    public function rules(): array
    {
        /** @var Student $student */
        $student = $this->route('student');
        $user = User::query()
            ->where('email', $student->email)
            ->first();
        $userAccount = UserAccount::query()->find($student->user_account_id);

        return [
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'address' => ['required', 'string', 'min:5', 'max:1000'],
            'contact' => ['required', 'digits:11'],
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('user_accounts', 'username')->ignore($userAccount?->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($student->id),
                Rule::unique('users', 'email')->ignore($user?->id),
                Rule::unique('user_accounts', 'email')->ignore($userAccount?->id),
            ],
            'degree_id' => ['required', 'integer', 'exists:degrees,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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
